<?php

namespace App\Console\Commands;

use App\Models\ContentRecord;
use App\Models\Project;
use App\Support\PortfolioDesign;
use App\Support\ResumeContent;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ImportProjectDetails extends Command
{
    protected $signature = 'app:import-project-details';
    protected $description = 'Vervang eerdere project-startteksten door de bevestigde aanvulling; behoud eigen redactie en publicatiestatus';

    public function handle(): int
    {
        $updated = DB::transaction(function (): int {
            $count = 0;
            foreach (PortfolioDesign::projects() as $source) {
                $record = Project::where('slug', $source['slug'])->first();
                if (! $record) continue;
                $legacy = $this->previousProject($source['slug']);
                $count += $this->replaceUnedited($record, $legacy, $source);
            }

            $initiative = ContentRecord::where('key', 'initiative:0')->where('kind', 'initiative')->first();
            if ($initiative && $initiative->organization?->name === 'DutchUnitedRacing') {
                $source = ResumeContent::initiatives()[0];
                $count += $this->replaceUnedited($initiative, [
                    'title' => 'Oprichter',
                    'summary' => 'Een simracingcommunity opzetten met aandacht voor eerlijke, toegankelijke online races voor beginners en ervaren coureurs.',
                ], ['title' => $source['role'], 'summary' => $source['summary']]);
                $data = $initiative->data ?? [];
                if (($data['responsibilities'] ?? null) === ['Werken aan een ondersteunende community waar raceplezier, integriteit en ontwikkeling centraal staan.']) {
                    $data['responsibilities'] = $source['responsibilities'];
                    $initiative->update(['data' => $data]);
                    $count++;
                }
            }
            return $count;
        });

        $this->info($updated.' oorspronkelijke velden bijgewerkt. Eigen redactie, afbeeldingen, slugs en publicatiestatus zijn behouden.');
        return self::SUCCESS;
    }

    private function replaceUnedited(Model $record, array $previous, array $source): int
    {
        $changes = [];
        foreach ($previous as $field => $value) {
            if ($record->getAttribute($field) === $value && array_key_exists($field, $source) && $source[$field] !== $value) $changes[$field] = $source[$field];
        }
        if ($changes) $record->update($changes);
        return count($changes);
    }

    private function previousProject(string $slug): array
    {
        return match ($slug) {
            'globetrotr' => [
                'name' => 'Globetrotr', 'type' => 'Project', 'headline' => 'globetrotr.', 'subheadline' => 'Een verhaal in wording.', 'role' => null,
                'summary' => 'Een van de projecten waar ik aan werk. Het verhaal achter het idee krijgt hier binnenkort een plek.',
                'description' => 'Globetrotr is een project waar ik aan werk. Hier komt het verhaal achter het idee en mijn bijdrage. De beschrijving, technische keuzes en beelden worden nog aangevuld.',
                'technologies' => [], 'chapters' => [],
            ],
            'domenicrokers' => [
                'summary' => 'Een eigen plek voor mijn verhaal, werk en projecten. Met een beheeromgeving die met me meegroeit.',
                'description' => 'Eén plek voor mijn ervaring, mijn projecten en de dingen die ik onderweg leer. Met een eigen beheeromgeving voor profiel, cv, bedrijfslogo’s, pagina’s en projecten.',
                'chapters' => [],
            ],
            'dutchunitedracing' => [
                'type' => 'Simracingcommunity', 'subheadline' => 'Dezelfde passie.', 'role' => 'Oprichter',
                'summary' => 'Mijn eigen simracinginitiatief. Met DutchUnitedRacing wil ik eerlijke, toegankelijke online races en een betrokken community samenbrengen.',
                'description' => 'In december 2023 begon ik DutchUnitedRacing. Het doel is een toegankelijke racecommunity: een plek voor beginners en ervaren coureurs, met eerlijke online races en aandacht voor elkaar.',
                'technologies' => [],
                'chapters' => [
                    ['title' => 'Een eerlijke start', 'body' => 'Fair play en integriteit staan centraal in wat ik wil opbouwen. Een prettige race-ervaring begint bij respect voor elkaar.'],
                    ['title' => 'Samen verder', 'body' => 'Mensen verbinden, van elkaar leren en samen de passie voor racen delen.'],
                ],
            ],
            default => [],
        };
    }
}
