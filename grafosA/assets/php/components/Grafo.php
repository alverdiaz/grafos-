<?php
include("./assets/php/components/Arista.php");
include("./assets/php/components/Vertice.php");

class Grafo
{
	private $vertices;
	private $aristas;
	private $contador;

	public function __construct()
	{
		$this->vertices = [];
		$this->aristas = [];
		$this->contador = 0;
	}

	public function getAristas()
	{
		return $this->aristas;
	}

	public function getVertices()
	{
		return $this->vertices;
	}

	public function setAristas($aristas)
	{
		$this->aristas = $aristas;
	}

	public function setVertices($vertices)
	{
		$this->vertices = $vertices;
	}

	public function buscarVertice($label)
	{
		$i = 0;
		foreach ($this->vertices as $vertice) {
			if ($vertice->getNombre() == $label) {
				return $vertice->getId();
			}
			$i = $i + 1;
		}
		return -1;
	}

	public function buscarVerticeId($id)
	{
		$i = 0;
		foreach ($this->vertices as $vertice) {
			if ($vertice->getId() == $id) {
				return $i;
			}
			$i = $i + 1;
		}
		return -1;
	}

	public function agregarVertice($label)
	{
		if ($this->buscarVertice($label) == -1) {
			$vertice = new Vertice($this->contador, $label);
			$this->vertices[$this->contador] = $vertice;
			$this->contador = $this->contador + 1;
			return "Vértice agregado correctamente";
		} else {
			return "El nombre del vértice ya existía en el grafo (no se pueden repetir)";
		}
	}

	public function eliminarVertice($label)
	{
		$pos = $this->buscarVertice($label);
		if ($pos != -1) {
			$i = 0;
			$id = $this->vertices[$pos]->getId();
			foreach ($this->aristas as $arista) {
				if ($arista->getVerticeInicio() == $id || $arista->getVerticeFin() == $id) {
					unset($this->aristas[$i]);
				}
				$i = $i + 1;
			}
			unset($this->vertices[$pos]);
			return "Vértice eliminado correctamente";
		} else {
			return "El nombre del vértice digitado no existía en el grafo";
		}
	}

	public function buscarArista($verticeInicio, $verticeFin)
	{
		$i = 0;
		foreach ($this->aristas as $arista) {
			if ($arista->getVerticeInicio() == $verticeInicio && $arista->getVerticeFin() == $verticeFin) {
				return $i;
			}
			$i = $i + 1;
		}
		return -1;
	}

	public function eliminarArista($verticeInicio, $verticeFin)
	{
		$pos1 = $this->buscarVerticeId($verticeInicio);
		$pos2 = $this->buscarVerticeId($verticeFin);
		if ($pos1 != -1 && $pos2 != -1) {
			$pos3 = $this->buscarArista($verticeInicio, $verticeFin);
			if ($pos3 != -1) {
				unset($this->aristas[$pos3]);
				return "Arista eliminada correctamente";
			} else {
				$verticeInicio = $this->vertices[$pos1]->getNombre();
				$verticeFin = $this->vertices[$pos2]->getNombre();
				return "No existe una arista que vaya del vértice $verticeInicio al vértice $verticeFin";
			}
		} else {
			return "Alguno o ambos vértices digitados no existen en el grafo";
		}
	}

	public function agregarArista($verticeInicio, $verticeFin, $valor)
	{
		$pos1 = $this->buscarVerticeId($verticeInicio);
		$pos2 = $this->buscarVerticeId($verticeFin);
		if ($pos1 != -1 && $pos2 != -1) {
			$pos = $this->buscarArista($verticeInicio, $verticeFin);
			if ($pos == -1) {
				$arista = new Arista($verticeInicio, $verticeFin, $valor);
				array_push($this->aristas, $arista);
				return "Arista añadida correctamente";
			} else {
				$verticeInicio = $this->vertices[$pos1]->getNombre();
				$verticeFin = $this->vertices[$pos2]->getNombre();
				return "Ya existe una arista que va del vértice $verticeInicio al vértice $verticeFin";
			}
		} else {
			return "Alguno de los vértices digitados no existe en el grafo";
		}
	}

	public function editarArista($verticeInicio, $verticeFin, $valor)
	{
		$pos = $this->buscarArista($verticeInicio, $verticeFin);
		if ($pos != -1) {
			$this->aristas[$pos]->setValor($valor);
			return "Arista editada correctamente";
		} else {
			return "La arista no existe";
		}
	}

	public function verticesAJson()
	{
		$out = [];
		foreach ($this->vertices as $vertice) {
			array_push($out, ["id" => $vertice->getId(), "label" => $vertice->getNombre()]);
		}
		return json_encode($out);
	}

	public function aristasAJson()
	{
		$out = [];
		foreach ($this->aristas as $arista) {
			array_push($out, ["from" => $arista->getVerticeInicio(), "to" => $arista->getVerticeFin(), "value" => $arista->getValor(), "label" => $arista->getValor(), "title" => "Valor: " . $arista->getValor()]);
		}
		return json_encode($out);
	}

	public function esVacio()
	{
		return count($this->vertices) == 0 && count($this->aristas) == 0;
	}

	private function containsAristaJson($x, $arr)
	{
		foreach ($arr as $item) {
			if ($item['from'] == $x['from'] && $item['to'] == $x['to']) {
				return true;
			}
		}
		return false;
	}

	public function recorridoProfundidad($verticeInicio)
	{
		// Busca el id del vertice de inicio usando su nombre
		$idVertice = $this->buscarVertice($verticeInicio);
		if (isset($this->aristas) && $idVertice != -1) {
			// Busca la primera arista del vertice por el cual se inicia el recorrido
			$aristaInicio = null;
			foreach ($this->aristas as $arista) {
				print_r($arista);
				if ($arista->getVerticeInicio() == $idVertice) {
					$aristaInicio = $arista;
					break;
				}
			}
			// Si la encuentra
			if ($aristaInicio != null) {
				$recorrido = [];
				$visitados = [];
				// Crea el arreglo de visitados para saber que vertices han sido visitados
				foreach ($this->vertices as $vertice) {
					$visitados[$vertice->getId()] = false;
				}
				$pila = [];
				// Agrega el vertice de incio a la pila
				array_push($pila, $aristaInicio->getVerticeInicio());
				while (count($pila) > 0) {
					// Saca un vertice de la pila (desde el final)
					$verticeActual = array_pop($pila);
					// Agrega el vertice anterior al recorrido
					array_push($recorrido, $this->vertices[$this->buscarVerticeId($verticeActual)]);
					// Y lo marca como visitado
					$visitados[$verticeActual] = true;
					// Recorre el arreglo de aristas buscando los vértices a los que apunta
					// el vertice actual para agregarlos a la pila en caso de que no hayan sido visitados aún
					foreach ($this->aristas as $arista) {
						if ($arista->getVerticeInicio() == $verticeActual && !$visitados[$arista->getVerticeFin()]) {
							array_push($pila, $arista->getVerticeFin());
						}
					}
				}
				return $recorrido;
			}
			return null;
		}
		return null;
	}

	public function recorridoAnchura($verticeInicio)
	{
		// Busca el id del vertice de inicio usando su nombre
		$idVertice = $this->buscarVertice($verticeInicio);
		if (isset($this->aristas) && $idVertice != -1) {
			// Busca la primera arista del vertice por el cual se inicia el recorrido
			$aristaInicio = null;
			foreach ($this->aristas as $arista) {
				print_r($arista);
				if ($arista->getVerticeInicio() == $idVertice) {
					$aristaInicio = $arista;
					break;
				}
			}
			// Si la encuentra
			if ($aristaInicio != null) {
				$recorrido = [];
				$visitados = [];
				// Crea el arreglo de visitados para saber que vertices han sido visitados
				foreach ($this->vertices as $vertice) {
					$visitados[$vertice->getId()] = false;
				}
				$cola = [];
				// Agrega el vertice de incio a la cola
				array_push($cola, $aristaInicio->getVerticeInicio());
				while (count($cola) > 0) {
					// Saca un vertice de la cola (desde el inicio)
					$verticeActual = array_shift($cola);
					// Agrega el vertice anterior al recorrido
					array_push($recorrido, $this->vertices[$this->buscarVerticeId($verticeActual)]);
					// Y lo marca como visitado
					$visitados[$verticeActual] = true;
					// Recorre el arreglo de aristas buscando los vértices a los que apunta
					// el vertice actual para agregarlos a la cola en caso de que no hayan sido visitados aún
					foreach ($this->aristas as $arista) {
						if ($arista->getVerticeInicio() == $verticeActual && !$visitados[$arista->getVerticeFin()]) {
							array_push($cola, $arista->getVerticeFin());
						}
					}
				}
				return $recorrido;
			}
			return null;
		}
		return null;
	}

	public function Dijkstra($inicio, $fin)
	{
		$vertices = [];
		$vecinos = [];
		$path = [];

		foreach ($this->aristas as $arista) {
			array_push($vertices, $arista->getVerticeInicio(), $arista->getVerticeFin());
			$vecinos[$arista->getVerticeInicio()][] = array("verticeFin" => $arista->getVerticeFin(), "costo" => $arista->getValor());
		}

		$distancia = [];
		$anterior = [];
		$vertices = array_unique($vertices);

		foreach ($vertices as $vertice) {
			$id = $vertice;
			$distancia[$id] = INF;
			$anterior[$id] = NULL;
		}

		$u = NULL;
		$distancia[$inicio] = 0;
		$cola = $vertices;

		while (count($cola) > 0) {
			$min = INF;
			foreach ($cola as $vertice) {
				if ($distancia[$vertice] < $min) {
					$min = $distancia[$vertice];
					$u = $vertice;
				}
			}

			$cola = array_diff($cola, array($u));
			if ($distancia[$u] == INF || $u == $fin) {
				break;
			}

			if (!isset($vecinos[$u]) && $min == INF && $u == $inicio) {
				break;
			}

			if (isset($vecinos[$u])) {
				foreach ($vecinos[$u] as $arr) {
					$alt = $distancia[$u] + $arr['costo'];
					if ($alt < $distancia[$arr['verticeFin']]) {
						$distancia[$arr['verticeFin']] = $alt;
						$anterior[$arr['verticeFin']] = $u;
					}
				}
			}
		}

		$u = $fin;
		while (isset($anterior[$u])) {
			array_unshift($path, array("from" => $anterior[$u], "to" => $u));
			$u = $anterior[$u];
		}
		$out = [];
		foreach ($this->aristas as $arista) {
			$aristaActual = ["from" => $arista->getVerticeInicio(), "to" => $arista->getVerticeFin(), "value" => $arista->getValor(), "label" => $arista->getValor(), "title" => "Valor: " . $arista->getValor()];
			if ($this->containsAristaJson($aristaActual, $path)) {
				$aristaActual['color'] = "red";
			}
			array_push($out, $aristaActual);
		}
		return json_encode($out);
	}
}
