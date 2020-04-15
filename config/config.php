<?php

//----------------------------------------------------------
// Configuration
//----------------------------------------------------------

return [
    /**
     * The namespace for Actions.
     */
    'actionNamespace' => 'App\Actions',

    /**
     * The namespace for Redirectors.
     */
    'redirectorNamespace' => 'App\Redirectors',

    /**
     * The namespace for Modifiers.
     */
    'modifierNamespace' => 'App\Modifiers',

    /**
     * The action that should handle errors.
     */
    'errorAction' => App\Actions\HandleError::class,

    /**
     * The class for users.
     */
    'userClass' => App\Models\Student::class,

    /**
     * The class that holds the "current" data.
     */
    'currentClass' => App\Current::class
];
