<?php

class Arista {
	private $verticeInicio;
	private $verticeFin;
	private $valor;

	public function __construct($verticeInicio, $verticeFin, $valor) {
		$this->verticeInicio = $verticeInicio;
		$this->verticeFin = $verticeFin;
		$this->valor = $valor;
	}

	public function getVerticeInicio() {
		return $this->verticeInicio;
	}

	public function getVerticeFin() {
		return $this->verticeFin;
	}

	public function getValor() {
		return $this->valor;
	}

	public function setVerticeInicio($verticeInicio) {
		$this->verticeInicio = $verticeInicio;
	}

	public function setVerticeFin($verticeFin) {
		$this->verticeFin = $verticeFin;
	}

	public function setValor($valor) {
		$this->valor = $valor;
	}
}