<?php

namespace App\ex10\Controller;

use App\ex10\Entity\File;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\ex10\Service\SqlDatabaseService;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;

class E10Controller extends AbstractController
{
    private SqlDatabaseService $sqlService;

    public function __construct(SqlDatabaseService $sqlService) {
        $this->sqlService = $sqlService;
    }

    #[Route('/ex10/upload-file', name: 'upload_file', methods: ['GET', 'POST'])]
    public function uploadFile(Request $request, EntityManagerInterface $em): Response
    {
        
        $defaultFilePath = 'file/default'; 
        if ($request->isMethod('POST')) {
            $file = $request->files->get('file');

            if ($file instanceof UploadedFile) {
                $fileName = $file->getClientOriginalName();
                $filePath = $file->getPathname();
                
                if (!is_readable($filePath)) {
                    return new Response("File is not readable", 403);
                }

                $content = file_get_contents($filePath);
                $createdAt = new \DateTime();
            } else {
                if (!is_readable($defaultFilePath)) {
                    return new Response("Default file is not readable", 403);
                }
                $content = file_get_contents($defaultFilePath);
                $fileName = basename($defaultFilePath); 
                $createdAt = new \DateTime();
            }

            try {
                $fileEntity = new File();
                $fileEntity->setName($fileName);
                $fileEntity->setContent($content);
                $fileEntity->setCreatedAt($createdAt);

                $em->persist($fileEntity);
                $em->flush();
            } catch (FileException $e) {
                $this->addFlash('error', 'There was an error uploading the file.');
            } catch (UniqueConstraintViolationException $e) {
                return new Response('Error: Filename already exists.', Response::HTTP_CONFLICT);
            } catch (\PDOException $e) {
                return new Response('Database Error: ' . $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
            } catch (\Exception $e) {
                return new Response('Error: ' . $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            $tableName = $this->sqlService->getTableName();
            $createdAtFormatted = $createdAt->format('Y-m-d H:i:s');
            $command = "
                INSERT IGNORE INTO $tableName (name, content, created_at)
                VALUES ('$fileName', '$content', '$createdAtFormatted');
            ";
            try {
                $result = $this->sqlService->execute($command);
            } catch (\Exception $e) {
                return new Response("Error: $e");
            }
            $this->addFlash('success', 'File uploaded successfully!');
            return $this->redirectToRoute('list_files');
        }
        return $this->render('file/upload.html.twig');
    }

    #[Route('/ex10/files', name: 'list_files', methods: ['GET'])]
    public function listFiles(EntityManagerInterface $em): Response
    {
        
        try {
            $filesORM = $em->getRepository(File::class)->findAll();
            
            $tableName = $this->sqlService->getTableName();
            $command = "
                SELECT * FROM $tableName;
            ";


            $filesSql = $this->sqlService->query($command);

            $filesSqlMapped = array_map(function ($row) {
                $file = new File();
                $file->setName($row['name']);
                $file->setContent($row['content']);
                $file->setCreatedAt(new \DateTime($row['created_at']));
                return $file;
            }, $filesSql);
        
            return $this->render('file/files.html.twig', [
                'ormfiles' => $filesORM,
                'sqlfiles' => $filesSqlMapped,
            ]);
        } catch (\Exception $e) {
            return new Response("Error: $e");
        }
    }

}


?>