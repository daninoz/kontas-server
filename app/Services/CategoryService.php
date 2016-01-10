<?php

namespace App\Services;

use Validator;
use App\Category;

class CategoryService
{
    /**
     * Category model
     *
     * @var Category
     */
    protected $category;

    /**
     * CategoryService constructor.
     *
     * @param Category    $category
     */
    public function __construct(Category $category)
    {
        $this->category = $category;
    }

    /**
     * Get a list of categories
     *
     * @return static
     */
    public function getList()
    {
        $categories = $this->category->all();

        $response = $categories->map(function ($item) {
            return [
                "id" => $item->id,
                "name" => $item->name,
            ];
        });

        return $response;
    }

    /**
     * Validate the input
     *
     * @param      $input
     * @param bool $update
     * @param null $id
     *
     * @throws \Exception
     */
    public function validateInput($input, $update = false, $id = null)
    {
        $rules = [
            'name' => ['required', 'max:100', 'unique:categories,name'],
        ];

        if ($update) {
            $rules['name'][2] .= "," . $id;
        }

        $validator = Validator::make($input, $rules);

        if ($validator->fails()) {
            throw new \Exception;
        }
    }

    /**
     * Creates a new category
     *
     * @param $input
     *
     * @return array
     */
    public function create($input)
    {
        $category = $this->category->create([
            'name' => $input->name
        ]);

        return ["id" => $category->id];
    }

    /**
     * Updates a category
     *
     * @param $input
     * @param $id
     *
     * @return array
     */
    public function update($input, $id)
    {
        $category = $this->category->findOrFail($id);

        $category->update([
            'name' => $input->name
        ]);

        return ["id" => $category->id];
    }
}