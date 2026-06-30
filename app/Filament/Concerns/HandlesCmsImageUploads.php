<?php

namespace App\Filament\Concerns;

use App\Domains\Content\Support\CmsImage;
use Filament\Resources\Pages\EditRecord;

trait HandlesCmsImageUploads
{
    /**
     * @param  list<string>  $fields
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function hydrateCmsImageFields(array $data, array $fields = ['image_path']): array
    {
        foreach ($fields as $field) {
            if (empty($data[$field]) || ! is_string($data[$field])) {
                continue;
            }

            if (CmsImage::isExternalUrl($data[$field])) {
                $data[$field] = [];

                continue;
            }

            $data[$field] = [$data[$field]];
        }

        return $data;
    }

    /**
     * @param  list<string>  $fields
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function normalizeCmsImageFields(array $data, array $fields = ['image_path']): array
    {
        foreach ($fields as $field) {
            if (! array_key_exists($field, $data)) {
                continue;
            }

            $value = $data[$field];

            if (is_array($value)) {
                $uploaded = $value[0] ?? null;

                if (filled($uploaded)) {
                    $data[$field] = $uploaded;

                    continue;
                }

                if ($this instanceof EditRecord) {
                    $existing = $this->getRecord()->getAttribute($field);
                } elseif (method_exists($this, 'getUser')) {
                    $existing = $this->getUser()->getAttribute($field);
                } else {
                    $existing = null;
                }

                if (filled($existing)) {
                    $data[$field] = $existing;

                    continue;
                }

                $data[$field] = null;
            }
        }

        return $data;
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeFill(array $data): array
    {
        return $this->hydrateCmsImageFields($data);
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return $this->normalizeCmsImageFields($data);
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        return $this->normalizeCmsImageFields($data);
    }
}
