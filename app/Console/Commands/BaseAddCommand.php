<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use function Laravel\Prompts\error;

abstract class BaseAddCommand extends Command
{
    protected function chooseFromModel(string $model, string $column, string $question): int
    {
        $items = $model::pluck($column, 'id')->toArray();

        if (empty($items)) {
            $this->error("таблица {$model} пуста, сначала создайте записи");
            die();
        }

        $chosen = $this->choice($question, $items);
        return $model::where($column, $chosen)->value('id');
    }
    protected function askRequired(string $question): string
    {
        while (empty($value = $this->ask($question))) {
            $this->error('поле не может быть пустым');
        }
        return $value;
    }

    protected function askOptional(string $question): ?string
    {
        return $this->ask($question) ?: null;
    }
}
