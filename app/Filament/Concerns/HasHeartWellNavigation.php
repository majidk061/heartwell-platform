<?php

namespace App\Filament\Concerns;

use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Pages\ViewRecord;

trait HasHeartWellNavigation
{
    /**
     * @return array<int, Action|Actions\ActionGroup>
     */
    protected function getFormActions(): array
    {
        return [
            $this->getPrimaryFormAction(),
            Action::make('saveAndBack')
                ->label('Save & back to list')
                ->color('gray')
                ->action(function (): void {
                    $this->save(shouldRedirect: false, shouldSendSavedNotification: true);
                    $this->redirect($this->getResource()::getUrl('index'));
                }),
            $this->getCancelFormAction(),
        ];
    }

    protected function getPrimaryFormAction(): Action
    {
        return $this instanceof CreateRecord
            ? $this->getCreateFormAction()
            : $this->getSaveFormAction();
    }

    protected function getCancelFormAction(): Action
    {
        $url = $this instanceof ViewRecord
            ? $this->getResource()::getUrl('index')
            : $this->getResource()::getUrl('index');

        return Action::make('cancel')
            ->label('Back to list')
            ->url($url)
            ->color('gray');
    }

    /**
     * @return array<int, Actions\Action>
     */
    protected function getHeaderActionsForEdit(): array
    {
        $actions = [];

        if (method_exists($this, 'getResource')) {
            $actions[] = Actions\Action::make('back')
                ->label('Back to list')
                ->icon('heroicon-o-arrow-left')
                ->color('gray')
                ->url($this->getResource()::getUrl('index'));
        }

        return $actions;
    }
}
