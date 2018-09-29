<?php

namespace Reallyli\AB\Commands;

use Reallyli\AB\Models\Goal;
use Illuminate\Console\Command;
use Reallyli\AB\Models\Experiment;
use Symfony\Component\Console\Helper\Table;

class ReportCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'ab:report';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Print the A/B testing report.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $experiments = Experiment::active()->get();
        $goals = array_unique(Goal::active()->orderBy('name')->pluck('name')->toArray());

        $columns = array_merge(['Experiment', 'Visitors', 'Engagement'], array_map('ucfirst', $goals));

        $table = new Table($this->output);
        $table->setHeaders($columns);

        foreach ($experiments as $experiment) {
            $engagement = $experiment->visitors ? ($experiment->engagement / $experiment->visitors * 100) : 0;

            $row = [
                $experiment->name,
                $experiment->visitors,
                number_format($engagement, 2).' % ('.$experiment->engagement.')',
            ];

            $results = $experiment->goals()->pluck('count', 'name');

            foreach ($goals as $column) {
                $count = array_get($results, $column, 0);
                $percentage = $experiment->visitors ? ($count / $experiment->visitors * 100) : 0;

                $row[] = number_format($percentage, 2)." % ($count)";
            }

            $table->addRow($row);
        }

        $table->render();
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [];
    }
}
