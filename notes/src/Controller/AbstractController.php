<?php

declare(strict_types=1);

namespace App\Controller;

use App\Request;
use App\View;
use App\Exception\ConfigurationException;
use App\Exception\NotFoundException;
use App\Exception\StorageException;
use App\Model\NoteModel;

abstract class AbstractController
{
  protected const DEFAULT_ACTION = 'list';

  private static array $configuration = [];

  protected NoteModel $noteModel;
  protected Request $request;
  protected View $view;

  public static function initConfiguration(array $configuration): void
  {
    self::$configuration = $configuration;
  }

  public function __construct(Request $request)
  {
    if (empty(self::$configuration['db'])) {
      throw new ConfigurationException('Configuration error');
    }
    $this->noteModel = new NoteModel(self::$configuration['db']);

    $this->request = $request;
    $this->view = new View();
  }

  final public function run(): void
  {
    try {
      $action = $this->action() . 'Action';
      if (!method_exists($this, $action)) {
        $action = self::DEFAULT_ACTION . 'Action';
      }
      $this->$action();
    } catch (StorageException $e) {
      // Log::error($e->getPrevios());
      $this->view->render('error', ['message' => $e->getMessage()]);
    } catch (NotFoundException $e) {
      $this->redirect('/', ['error' => 'noteNotFound']);
    }
  }

  final protected function redirect(string $to, array $params): void
  {
    $location = $to;

    if (count($params)) {
      $queryParams = [];
      foreach ($params as $key => $value) {
        $queryParams[] = urlencode($key) . '=' . urlencode($value);
      }
      $queryParams = implode('&', $queryParams);
      $location .= '?' . $queryParams;
    }

    header("Location: $location");
    exit;
  }

  final private function action(): string
  {
    return $this->request->getParam('action', self::DEFAULT_ACTION);
  }
}
