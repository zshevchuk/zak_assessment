<?php
namespace App\Command;

use App\Lib\FileParser\Contracts\DataProcessor;
use App\Lib\FileParser\RenderedFactory;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

use App\Lib\FileParser\InputFile;
use App\Lib\FileParser\OutputFile;
use App\Lib\FileParser\ParserFactory;
use App\Lib\FileParser\LogDataProcessor;

class FileParserCommand extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'zak:parse';
    private SymfonyStyle $io;

    protected function configure(): void
    {
        $this
            ->setDescription('Parses xml/csv provided file(s)')
            ->setHelp('Supply 2 arguments, input file(files with comma separator) and name of output file')
            ->addArgument('input_files', InputArgument::IS_ARRAY | InputArgument::REQUIRED, 'File name or names(with space separator)')
            ->addOption('output_file', 'o', InputOption::VALUE_REQUIRED, 'File name for a result file', OutputFile::getDefaultName());
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = $this->io = new SymfonyStyle($input, $output);

        try {
            $inputFilesPaths = $input->getArgument('input_files');
            $outputFilePath = $input->getOption('output_file');

            $analyzedData = $this->parseInputs($inputFilesPaths);

            $this->io->text('Crunching numbers');

            $result = $analyzedData->response();

            $this->io->section('Preparing a result');
            $this->io->text('Rendering an output file');
            $this->renderResultFile($outputFilePath, $result);

            $io->title(sprintf('Data has been processed, you can find a result in %s/%s', OutputFile::$folder, $outputFilePath));
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $io->error($e->getMessage());

            return Command::FAILURE;
        }
    }

    /**
     * Gather information from files and prepare it for render
     */
    protected function parseInputs(array $inputFilesPaths): DataProcessor
    {
        $this->io->section('Parsing input file(s)');
        $resultData = new LogDataProcessor();

        foreach ($inputFilesPaths as $inputFilesPath) {
            $file = new InputFile($inputFilesPath);

            $fileParser = ParserFactory::create($file);

            $parsed = $fileParser->parse($file, $resultData);

            if (!$parsed) {
                $this->io->text('An error has occurred while parsing: ' . $inputFilesPath);
                continue;
            }

            $this->io->text($inputFilesPath . ' was parsed');
        }

        if (!$resultData->isInitialized()) {
            throw new \Exception('No data was gather from files');
        }

        return $resultData;
    }

    /**
     * Gets corresponding renderer and tries to render an output file
     */
    protected function renderResultFile($outputFilePath, $analyzedData): void
    {
        $resultFile = new OutputFile($outputFilePath);

        RenderedFactory::create($resultFile)
            ->render($analyzedData);
    }

}


