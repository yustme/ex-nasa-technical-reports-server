<?phpdeclare(strict_types=1);namespace NasaExtractor;use Keboola\Component\BaseComponent;use Keboola\Csv\CsvWriter;use GuzzleHttp\Client;use GuzzleHttp\Exception\RequestException;class Component extends BaseComponent{    protected function run(): void    {        $client = new Client();        $uri = 'https://ntrs.nasa.gov/api/citations/search?' . $this->getConfig()->getSearchQuery() . '&' . $this->getConfig()->getPaginationParams();        $this->getLogger()->info("Query" . $uri);        $response = $client->get($uri);        $data = json_decode($response->getBody()->getContents(), true);        $writer = new CsvWriter('/data/out/tables/nasa.csv');        $writer->writeRow(['id', 'data', 'fulltext']);        foreach ($data['results'] as $item) {            $id = $item['id'];            $downloadUrl = "https://ntrs.nasa.gov/api/citations/$id/downloads";            $this->getLogger()->info("Downloading $downloadUrl");            try {                $detailData = json_decode($client->get($downloadUrl)->getBody()->getContents(), true);            } catch (RequestException $e) {                echo "no detail found \n";                continue;            }            $fullTextLink = 'https://ntrs.nasa.gov/' . $detailData[0]['links']['fulltext'];            try {                $fulltextContent = $client->get($fullTextLink)->getBody()->getContents();            } catch (RequestException $e) {                var_dump($e->getMessage());                exit();            }            $writer->writeRow([$id, json_encode([$item]), json_encode(['data' => $fulltextContent])]);        }    }    protected function getConfigClass(): string    {        return Config::class;    }    protected function getConfigDefinitionClass(): string    {        return ConfigDefinition::class;    }}