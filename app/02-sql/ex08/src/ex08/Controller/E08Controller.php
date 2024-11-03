<?php

namespace App\ex08\Controller;

use App\Service\PersonService;
use App\Service\BankService;
use App\Service\AddressService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Form\ColumnForm;

class E08Controller extends AbstractController
{
    private PersonService $personService;
    private BankService $bankService;
    private AddressService $addressService;
    private $tables = array("persons", "bank_accounts", "addresses");
    private array $tableServiceMap;

    public function __construct(
        PersonService $personService,
        BankService $bankService,
        AddressService $addressService
    ) {
        $this->personService = $personService;
        $this->bankService = $bankService;
        $this->addressService = $addressService;
        $this->tableServiceMap = [
            'persons' => $this->personService,
            'bank_accounts' => $this->bankService,
            'addresses' => $this->addressService,
        ];
    }

    #[Route('/ex08', name: 'ex08')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'tables' => $this->tables
        ]);
    }
    
    #[Route('/ex08/create-table/{table}', name: 'create_table')]
    public function createTable(string $table): Response 
    {
        try {
            $service = $this->getTableService($table);
            if (!$service) {
                return new Response("Service for table '$table' not found.", Response::HTTP_NOT_FOUND);
            }
        
            $service->createTable();
            return new JsonResponse(['message' => 'Table successfully created.'], Response::HTTP_OK);
        } catch (\Exception $e) {
            return new Response("Error: $e");
        }
    }
    
    #[Route('/ex08/get-table/{table}', name: 'table_schema')]
    public function getTable(string $table): Response 
    {
        try {
            $dbName = $this->getParameter('db_name');
            $service = $this->getTableService($table);
            if (!$service) {
                return new Response("Service for table '$table' not found.", Response::HTTP_NOT_FOUND);
            }
            $tableSchema = $service->getTable($dbName);
            return new JsonResponse(['message' => $tableSchema], Response::HTTP_OK);
        } catch (\Exception $e) {
            return new Response("Error: $e");
        }
    }

    #[Route('/ex08/drop-table/{table}', name: 'drop_table')]
    public function dropTable(string $table): Response 
    {
        try {
            $dbName = $this->getParameter('db_name');
            $service = $this->getTableService($table);
            if (!$service) {
                return new Response("Service for table '$table' not found.", Response::HTTP_NOT_FOUND);
            }
            $service->dropTable($dbName);
            return new JsonResponse(['message' => 'Table successfully deleted.'], Response::HTTP_OK);
        } catch (\Exception $e) {
            return new Response("Error: $e");
        }
    }

    #[Route('/ex08/add-relationship', name: 'add_relationship')]
    public function addRelationship(Request $request): Response
    {
        if ($request->isMethod('POST')) {
            $relationshipType = $request->request->get('relationship_type');
            $tableOne = $request->request->get('table_one');
            $tableTwo = $request->request->get('table_two');
            if ($tableOne === $tableTwo) {
                return new Response("Error: Both tables cannot be the same.", Response::HTTP_BAD_REQUEST);
            }
    
            try {
                $service = $this->getTableService($tableOne);
                if (!$service) {
                    return new Response("Service for table '$tableOne' not found.", Response::HTTP_NOT_FOUND);
                }
                $service->addRelationship($relationshipType);
                return new JsonResponse(['message' => 'Relationship successfully created.'], Response::HTTP_OK);
            } catch (\Exception $e) {
                return new Response("Error: $e");
            }
        }
        $tables = array("persons", "bank_accounts");
        return $this->render('relationship/index.html.twig', [
            'tables' => $tables,
        ]);
    }

    #[Route('/ex08/add-column/{table}', name: 'add_column')]
    public function addColumn(string $table, Request $request): Response 
    {
        $form = $this->createForm(ColumnForm::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $columnName = $data['columnName'];
            $dataType = $data['dataType'];
            $nullable = $data['isNullable'];
            try {
                $service = $this->getTableService($table);
                if (!$service) {
                    return new Response("Service for table '$table' not found.", Response::HTTP_NOT_FOUND);
                }
                $service->addColumn($columnName, $dataType, $nullable);
                return new JsonResponse(['message' => 'Column successfully added.'], Response::HTTP_OK);
            } catch (\Exception $e) {
                return new Response("Error: $e");
            }
        }

        return $this->render('column/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    private function getTableService(string $tableName)
    {
        return $this->tableServiceMap[$tableName] ?? null;
    }

}


?>