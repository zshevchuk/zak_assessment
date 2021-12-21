<?php
namespace App\Command;

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
        $io = new SymfonyStyle($input, $output);

        $io->section('Parsing input file(s)');

        $inputFilesPaths = $input->getArgument('input_files');
        $outputFilePath = $input->getOption('output_file');

        $resultData = new LogDataProcessor();

        foreach ($inputFilesPaths as $inputFilesPath) {
            $file = new InputFile($inputFilesPath);

            $fileParser = ParserFactory::create($file);

            $parsed = $fileParser->parse($file, $resultData);

            if (!$parsed) {
                $io->text('An error has occurred while parsing: ' . $inputFilesPath );
                continue;
            }

            $io->text($inputFilesPath . ' was parsed');
        }

        $io->section('Preparing a result');

        $io->text('Crunching numbers');
        $result = $resultData->response();

        $resultFile = new OutputFile($outputFilePath);

        $renderer = RenderedFactory::create($resultFile);

        $io->text('Rendering an output file');

        if (!$renderer->render($result)) {
            throw new \Exception('Could not render output file');
        }

        $io->title(sprintf('Data has been processed, you can find a result in %s/%s', OutputFile::$folder, $outputFilePath));
        return Command::FAILURE;
    }
}


