<?php

namespace App\ex09\Controller;

use App\ex09\Entity\Person;
use App\ex09\Entity\BankAccount;
use App\ex09\Entity\Address;
use App\ex09\Enum\MaritalStatus;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;

class E09Controller extends AbstractController
{
    #[Route('/ex09/create-person', name: 'create_person', methods: ['POST'])]
    public function createPerson(EntityManagerInterface $em, Request $request): Response
    {
        try {
            if (empty($request->getContent())) {
                return new Response('Request body is empty.', Response::HTTP_BAD_REQUEST);
            }

            $data = $request->toArray();
            if (!isset($data['username']) || empty($data['username'])) {
                return new Response('Username is required.', Response::HTTP_BAD_REQUEST);
            }
            if (!isset($data['name']) || empty($data['name'])) {
                return new Response('Name is required.', Response::HTTP_BAD_REQUEST);
            }
            if (!isset($data['email']) || empty($data['email'])) {
                return new Response('Email is required.', Response::HTTP_BAD_REQUEST);
            }
            if (!isset($data['marital_status']) || empty($data['marital_status'])) {
                return new Response('Marital status is required.', Response::HTTP_BAD_REQUEST);
            }
            try {
                $maritalStatus = MaritalStatus::from($data['marital_status']);
            } catch (\ValueError $e) {
                return new Response('Invalid marital_status: ' . $data['marital_status'], Response::HTTP_BAD_REQUEST);
            }

            $person = new Person();
            $person->setUsername($data['username']);
            $person->setName($data['name']);
            $person->setEmail($data['email']);
            $person->setMaritalStatus($maritalStatus);

            $em->persist($person);
            $em->flush();

            return new Response('Person created with ID ' . $person->getId(), Response::HTTP_OK);
        } catch (UniqueConstraintViolationException $e) {
            return new Response('Error: User with the data already exists.', Response::HTTP_CONFLICT);
        } catch (\PDOException $e) {
            return new Response('Database Error: ' . $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            return new Response('Error: ' . $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/ex09/person/{personId}/add-bank-account', name: 'add_bank_account', methods: ['POST'])]
    public function addBankAccount(EntityManagerInterface $em, Request $request, int $personId): Response
    {
        
        try {
            if (empty($request->getContent())) {
                return new Response('Request body is empty.', Response::HTTP_BAD_REQUEST);
            }

            $data = $request->toArray();
            if (!isset($data['account_number']) || empty($data['account_number'])) {
                return new Response('Account number is required.', Response::HTTP_BAD_REQUEST);
            }
            $person = $em->getRepository(Person::class)->find($personId);

            if (!$person) {
                return new Response('Person not found', Response::HTTP_NOT_FOUND);
            }

            $bankAccount = new BankAccount();
            $bankAccount->setAccountNumber($data['account_number']);
            $bankAccount->setPerson($person);

            $em->persist($bankAccount);
            $em->flush();

            return new Response('BankAccount added to Person ID ' . $personId, Response::HTTP_OK);
        } catch (UniqueConstraintViolationException $e) {
            return new Response('Error: Account with the data already exists.', Response::HTTP_CONFLICT);
        } catch (\PDOException $e) {
            return new Response('Database Error: ' . $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            return new Response('Error: ' . $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/ex09/person/{personId}/set-address', name: 'set_address', methods: ['POST'])]
    public function setAddress(EntityManagerInterface $em, Request $request, int $personId): Response
    {
        try {
            if (empty($request->getContent())) {
                return new Response('Request body is empty.', Response::HTTP_BAD_REQUEST);
            }

            $data = $request->toArray();
            if (!isset($data['street']) || empty($data['street'])) {
                return new Response('Street is required.', Response::HTTP_BAD_REQUEST);
            }
            if (!isset($data['city']) || empty($data['city'])) {
                return new Response('City is required.', Response::HTTP_BAD_REQUEST);
            }
            if (!isset($data['country']) || empty($data['country'])) {
                return new Response('Country is required.', Response::HTTP_BAD_REQUEST);
            }

            $person = $em->getRepository(Person::class)->find($personId);

            if (!$person) {
                return new Response('Person not found', Response::HTTP_NOT_FOUND);
            }

            $address = new Address();
            $address->setStreet($data['street'])
                    ->setCity($data['city'])
                    ->setCountry($data['country'])
                    ->setPerson($person);

            $em->persist($address);
            $em->flush();

            return new Response('Address added to Person ID ' . $personId, Response::HTTP_OK);
        } catch (UniqueConstraintViolationException $e) {
            return new Response('Error: Address with the data already exists.', Response::HTTP_CONFLICT);
        } catch (\PDOException $e) {
            return new Response('Database Error: ' . $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            return new Response('Error: ' . $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/ex09/bank-account/{bankAccountId}', name: 'account_number', methods: ['POST'])]
    public function updateBankAccount(EntityManagerInterface $em, Request $request, int $bankAccountId): Response
    {
        try {
            if (empty($request->getContent())) {
                return new Response('Request body is empty.', Response::HTTP_BAD_REQUEST);
            }

            $data = $request->toArray();
            if (!isset($data['account_number']) || empty($data['account_number'])) {
                return new Response('Account number is required.', Response::HTTP_BAD_REQUEST);
            }
            $bankAccount = $em->getRepository(BankAccount::class)->find($bankAccountId);

            if (!$bankAccount) {
                return new Response('BankAccount not found', Response::HTTP_NOT_FOUND);
            }

            $bankAccount->setAccountNumber($data['account_number']);
            $em->flush();

            return new Response($bankAccountId . ' account is updated', Response::HTTP_OK);
        } catch (UniqueConstraintViolationException $e) {
            return new Response('Error: Account with the data already exists.', Response::HTTP_CONFLICT);
        } catch (\PDOException $e) {
            return new Response('Database Error: ' . $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            return new Response('Error: ' . $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}


?>