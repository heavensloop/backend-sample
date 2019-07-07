<?php

abstract class TestCase extends Laravel\Lumen\Testing\TestCase
{
    /**
     * Creates the application.
     *
     * @return \Laravel\Lumen\Application
     */
    public function createApplication()
    {
        return require __DIR__.'/../bootstrap/app.php';
    }

    protected function hasPaginationStructure($response) {
        $attributes = array_keys(get_object_vars($response));
        $expected_attributes = [
            "current_page",
            "data",
            "first_page_url",
            "from",
            "last_page",
            "last_page_url",
            "next_page_url",
            "path",
            "per_page",
            "prev_page_url",
            "to",
            "total"
        ];

        foreach($expected_attributes as $attribute) {
            if (!in_array($attribute, $attributes)) {
                throw new Exception("Property - {$attribute} not found in pagination response");
            }
        }

        return true;
    }
}
