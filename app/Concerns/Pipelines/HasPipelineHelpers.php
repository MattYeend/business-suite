<?php

namespace App\Concerns\Pipelines;

use App\Models\Pipeline;

/**
 * Pipeline entity helper methods.
 *
 * @property bool $is_active
 * @property bool $is_default
 * @property bool $is_real
 * @property string $entity
 */
trait HasPipelineHelpers
{
    /**
     * Check if the pipeline is active.
     *
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->is_active;
    }

    /**
     * Check if the pipeline is inactive.
     *
     * @return bool
     */
    public function isInactive(): bool
    {
        return ! $this->is_active;
    }

    /**
     * Check if the pipeline is the default for its entity entity.
     *
     * @return bool
     */
    public function isDefault(): bool
    {
        return $this->is_default;
    }

    /**
     * Check if the pipeline is real.
     *
     * @return bool
     */
    public function isReal(): bool
    {
        return $this->is_real;
    }

    /**
     * Determine whether the pipeline is of a given entity.
     *
     * @param  string $entity
     *
     * @return bool
     */
    public function isEntity(string $entity): bool
    {
        return $this->entity === $entity;
    }

    /**
     * Determine whether the entity is a deal.
     *
     * @return bool
     */
    public function isDeal(): bool
    {
        return $this->entity === Pipeline::DEAL_ENTITY;
    }

    /**
     * Determine whether the entity is an order.
     *
     * @return bool
     */
    public function isOrder(): bool
    {
        return $this->entity === Pipeline::ORDER_ENTITY;
    }

    /**
     * Determine whether the entity is a task.
     *
     * @return bool
     */
    public function isTask(): bool
    {
        return $this->entity === Pipeline::TASK_ENTITY;
    }

    /**
     * Determine whether the entity is a project.
     *
     * @return bool
     */
    public function isProject(): bool
    {
        return $this->entity === Pipeline::PROJECT_ENTITY;
    }

    /**
     * Determine whether the entity is a candidate.
     *
     * @return bool
     */
    public function isCandidate(): bool
    {
        return $this->entity === Pipeline::CANDIDATE_ENTITY;
    }

    /**
     * Determine whether the entity is a quote.
     *
     * @return bool
     */
    public function isQuote(): bool
    {
        return $this->entity === Pipeline::QUOTE_ENTITY;
    }

    /**
     * Get all available pipeline entities.
     *
     * @return array<int,string>
     */
    public static function getEntites(): array
    {
        return [
            Pipeline::DEAL_ENTITY,
            Pipeline::ORDER_ENTITY,
            pipeline::TASK_ENTITY,
            Pipeline::PROJECT_ENTITY,
            Pipeline::CANDIDATE_ENTITY,
            Pipeline::QUOTE_ENTITY,
        ];
    }

    /**
     * Get a human-readable entity label.
     *
     * @return string
     */
    public function getEntityLabel(): string
    {
        return match ($this->entity) {
            Pipeline::DEAL_ENTITY => 'Deal',
            Pipeline::ORDER_ENTITY => 'Order',
            Pipeline::TASK_ENTITY => 'Task',
            Pipeline::PROJECT_ENTITY => 'Project',
            Pipeline::CANDIDATE_ENTITY => 'Candidate',
            Pipeline::QUOTE_ENTITY => 'Quote',
            default => $this->entity ? ucfirst(
                str_replace('_', ' ', $this->entity)
            ) : null,
        };
    }
}
