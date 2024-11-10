<?php

namespace App\Base\Traits\Sort;

use Spatie\QueryBuilder\Sorts\Sort;
use Illuminate\Database\Eloquent\Builder;

class SortByThroughDirectedRelationship implements Sort
{
    public function __invoke(Builder $query, bool $descending, string $property)
    {
        $direction = $descending ? 'DESC' : 'ASC';
        $is_internal_app = 0;
        $query->raw('LEFT JOIN hr_sap_jobs ON hr_sap_jobs.id = hr_candidates.sap_job_id
        LEFT JOIN hr_sap_jobs ON hr_sap_jobs.id = hr_sap_jobs.id
        LEFT JOIN hr_internal_job_apps ON hr_internal_job_apps.sap_job_id = hr_sap_jobs.id AND hr_candidates.is_internal_app = 1
        LEFT JOIN hr_sap_job_translations ON hr_sap_job_translations.sap_job_id = hr_sap_jobs.id AND hr_candidates.is_internal_app= 1
        WHERE hr_candidates.sap_job_id = ?');
        //        $query->raw('CASE WHEN hr_candidates.is_internal_app = 0 THEN LEFT JOIN hr_sap_jobs ON hr_candidates.sap_job_id = hr_sap_jobs.id LEFT JOIN hr_sap_job_translations ON hr_sap_job_translations.sap_job_id = CASE WHEN hr_candidates.is_internal_app = 0 THEN hr_sap_jobs.id ELSE hr_internal_job_apps.sap_job_id END ORDER BY hr_sap_job_translations.name ' . $direction);
        //        $query->raw('LEFT JOIN hr_sap_jobs ON hr_candidates.sap_job_id = CASE WHEN hr_candidates.is_internal_app = 0 THEN hr_sap_jobs.id ELSE hr_internal_job_apps.sap_job_id END
        //            LEFT JOIN hr_sap_job_translations ON hr_sap_job_translations.sap_job_id = CASE WHEN hr_candidates.is_internal_app = 0 THEN hr_sap_jobs.id ELSE hr_internal_job_apps.sap_job_id END
        //         ORDER BY hr_sap_job_translations.name ' . $direction);
    }
}
