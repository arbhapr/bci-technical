<?php

namespace App\Controller;

use Error;
use DateTime;
use Throwable;
use App\Entity\Stages;
use DateTimeImmutable;
use App\Entity\Categories;
use App\Entity\Constructions;
use App\Repository\ConstructionsRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ConstructionController extends AbstractController
{
    private $repo;

    public function __construct(
        ConstructionsRepository $constructionsRepository,
    ) {
        $this->repo = $constructionsRepository;
    }

    #[Route('/construction', name: 'construction.index', methods: ['GET'])]
    public function list(Request $request): JsonResponse
    {
        try {
            $filter = $request->query->get('filter') ?? null;
            $constructions = $this->repo->getList($filter);

            $data = [];
            foreach ($constructions as $item) {
                $data[] = [
                    'id' => $item->getId(),
                    'name' => $item->getName(),
                    'location' => $item->getLocation(),
                    'stage' => $item->getStage()->value,
                    'category' => $item->getCategory()->value,
                    'otherCategory' => $item->getOtherCategory(),
                    'startDate' => $item->getStartDate()->format('Y-m-d'),
                    'description' => $item->getDescription(),
                    'creatorId' => $item->getCreatorId(),
                ];
            }

            return new JsonResponse([
                'status' => 'success',
                'data' => $data,
            ]);
        } catch (Throwable $th) {
            return new JsonResponse([
                'status' => 'error',
                'message' => $th->getMessage(),
            ], $th->getCode());
        }
    }

    #[Route('/construction', name: 'construction.store', methods: ['POST'])]
    public function store(Request $request): JsonResponse
    {
        try {

            $data = json_decode($request->getContent());

            $errors = $this->validation($data, "store");
            if (!empty($errors)) {
                return new JsonResponse([
                    'status' => 'error',
                    'message' => 'Validation failed.',
                    'errors' => $errors,
                ], JsonResponse::HTTP_BAD_REQUEST);
            }

            $stage = Stages::from($data->stage);
            $category = Categories::from($data->category);

            $construction = new Constructions();
            $construction->setName($data->name);
            $construction->setLocation($data->location);
            $construction->setStage($stage);
            $construction->setCategory($category);
            if ($category->value == "Others") {
                $construction->setOtherCategory($data->otherCategory ?? null);
            }
            $construction->setStartDate(new DateTime($data->startDate));
            $construction->setDescription($data->description);
            $construction->setCreatorId($data->creatorId ?? "user");

            $data = $this->repo->store($construction);

            return new JsonResponse([
                'status' => 'success',
                'data' => [
                    'id' => $data->getId(),
                    'name' => $data->getName(),
                    'location' => $data->getLocation(),
                    'stage' => $data->getStage()->value,
                    'category' => $data->getCategory()->value,
                    'otherCategory' => $data->getOtherCategory(),
                    'startDate' => $data->getStartDate()->format('Y-m-d'),
                    'description' => $data->getDescription(),
                    'creatorId' => $data->getCreatorId(),
                ],
            ]);
        } catch (Throwable $th) {
            return new JsonResponse([
                'status' => 'error',
                'message' => $th->getMessage(),
            ], $th->getCode());
        }
    }

    #[Route('/construction/{id}', name: 'construction.detail', methods: ['GET'])]
    public function detail(string $id): JsonResponse
    {
        try {
            $data = $this->repo->detail($id);

            if (!$data) {
                throw new Error('Construction not found.', JsonResponse::HTTP_NOT_FOUND);
            }

            return new JsonResponse([
                'status' => 'success',
                'data' => [
                    'id' => $data->getId(),
                    'name' => $data->getName(),
                    'location' => $data->getLocation(),
                    'stage' => $data->getStage()->value,
                    'category' => $data->getCategory()->value,
                    'otherCategory' => $data->getOtherCategory(),
                    'startDate' => $data->getStartDate()->format('Y-m-d'),
                    'description' => $data->getDescription(),
                    'creatorId' => $data->getCreatorId(),
                ],
            ]);
        } catch (Throwable $th) {
            return new JsonResponse([
                'status' => 'error',
                'message' => $th->getMessage(),
            ], $th->getCode());
        }
    }

    #[Route('/construction/{id}', name: 'construction.update', methods: ['PUT', 'PATCH'])]
    public function update(Request $request, string $id): JsonResponse
    {
        try {
            $data = $this->repo->detail($id);

            if (!$data) {
                throw new Error('Construction not found.', JsonResponse::HTTP_NOT_FOUND);
            }

            $request = json_decode($request->getContent(), true);
            if (!is_array($request)) {
                throw new \Exception('Invalid input data.', JsonResponse::HTTP_BAD_REQUEST);
            }
            $errors = $this->validation($request, "update");
            if (!empty($errors)) {
                return new JsonResponse([
                    'status' => 'error',
                    'message' => 'Validation failed.',
                    'errors' => $errors,
                ], JsonResponse::HTTP_BAD_REQUEST);
            }

            $data = $this->repo->update($id, $request);

            return new JsonResponse([
                'status' => 'success',
                'data' => [
                    'id' => $data->getId(),
                    'name' => $data->getName(),
                    'location' => $data->getLocation(),
                    'stage' => $data->getStage()->value,
                    'category' => $data->getCategory()->value,
                    'otherCategory' => $data->getOtherCategory(),
                    'startDate' => $data->getStartDate()->format('Y-m-d'),
                    'description' => $data->getDescription(),
                    'creatorId' => $data->getCreatorId(),
                ],
            ]);
        } catch (Throwable $th) {
            return new JsonResponse([
                'status' => 'error',
                'message' => $th->getMessage(),
            ], $th->getCode());
        }
    }

    #[Route('/construction/{id}', name: 'construction.destroy', methods: ['DELETE'])]
    public function delete(string $id): JsonResponse
    {
        try {
            $data = $this->repo->detail($id);

            if (!$data) {
                throw new Error('Construction not found.', JsonResponse::HTTP_NOT_FOUND);
            }
            
            $this->repo->delete($id);

            return new JsonResponse([
                'status' => 'success',
                'message' => 'Construction deleted successfully.',
            ]);
        } catch (Throwable $th) {
            return new JsonResponse([
                'status' => 'error',
                'message' => $th->getMessage(),
            ], $th->getCode());
        }
    }

    // private function
    private function validation($data, $method = "store"): array
    {
        $errors = [];
        if ($method == "store") {
            if (!isset($data->name) || empty($data->name) || strlen($data->name) > 200) {
                $errors[] = '`name` is required and must be less than 200 characters';
            }
            if (!isset($data->location) || empty($data->location)) {
                $errors[] = '`location` is required';
            }
            if (!isset($data->stage) || empty($data->stage) || (!empty($data->stage) && !in_array($data->stage, [Stages::CONCEPT->value, Stages::CONST->value, Stages::DESIGN->value, Stages::PRECONST->value]))) {
                $errors[] = '`stage` is required and must be a valid stage, such as "Concept", "Design & Documentation", "Construction" or "Pre-Construction"';
            }
            if (!isset($data->category) || empty($data->category) || (!empty($data->category) && !in_array($data->category, [Categories::EDUCATION->value, Categories::HEALTH->value, Categories::OFFICE->value, Categories::OTHERS->value]))) {
                $errors[] = '`category` is required and must be a valid category, such as "Education", "Health", "Office" or "Others"';
            } else if ($data->category === Categories::OTHERS->value && empty($data->otherCategory)) {
                $errors[] = '`otherCategory` is required when category is "Others"';
            }
            if (!isset($data->startDate) || empty($data->startDate) || !DateTime::createFromFormat('Y-m-d', $data->startDate)) {
                $errors[] = '`startDate` is required and must be a valid date in Y-m-d format';
            } else if (in_array($data->stage, [Stages::CONCEPT->value, Stages::DESIGN->value, Stages::PRECONST->value])) {
                $startDate = DateTime::createFromFormat('Y-m-d', $data->startDate);
                $now = new DateTimeImmutable();
                if ($startDate <= $now) {
                    $errors[] = '`startDate` must be a future date if `stage` is "Concept", "Design & Documentation" or "Pre-Construction"';
                }
            }
            if (!isset($data->description) || empty($data->description)) {
                $errors[] = '`description` is required';
            }
        } else if ($method == "update") {
            if (isset($data['name']) && !empty($data['name']) && strlen($data['name']) > 200) {
                $errors[] = '`name` must be less than 200 characters';
            }
            if (isset($data['stage']) && !empty($data['stage']) && !in_array($data['stage'], [Stages::CONCEPT->value, Stages::CONST->value, Stages::DESIGN->value, Stages::PRECONST->value])) {
                $errors[] = '`stage` must be a valid stage, such as "Concept", "Design & Documentation", "Construction" or "Pre-Construction"';
            }
            if (isset($data['category']) && !empty($data['category']) && !in_array($data['category'], [Categories::EDUCATION->value, Categories::HEALTH->value, Categories::OFFICE->value, Categories::OTHERS->value])) {
                $errors[] = '`category` must be a valid category, such as "Education", "Health", "Office" or "Others"';
            } else if (isset($data['category']) && !empty($data['category']) && $data['category'] === Categories::OTHERS->value && empty($data['otherCategory'])) {
                $errors[] = '`otherCategory` is required when category is "Others"';
            }
            if (isset($data['startDate']) && !empty($data['startDate']) && !DateTime::createFromFormat('Y-m-d', $data['startDate'])) {
                $errors[] = '`startDate` must be a valid date in Y-m-d format';
            } else if (isset($data['startDate']) && !empty($data['startDate']) && in_array($data['stage'], [Stages::CONCEPT->value, Stages::DESIGN->value, Stages::PRECONST->value])) {
                $startDate = DateTime::createFromFormat('Y-m-d', $data['startDate']);
                $now = new DateTimeImmutable();
                if ($startDate <= $now) {
                    $errors[] = '`startDate` must be a future date if `stage` is "Concept", "Design & Documentation" or "Pre-Construction"';
                }
            }
        }
        return $errors;
    }
}
