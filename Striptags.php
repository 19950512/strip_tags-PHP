<?php
/*
	{
		"AUTHOR":"Matheus Maydana",
		"CREATED_DATA": "14/03/2019",
		"CONTROLADOR": "StripTags",
		"LAST EDIT": "14/03/2019",
		"VERSION":"0.0.1"
	}
*/

class Plugins_Striptags {

	/**
	 * 
	 * Array para permitir Tags e permitir atributos
	 *
	 * @var array
	 */
	protected $tags_permitida = array();

	/**
	 * Array para permitir atributos em todas as tags
	 *
	 * @var array
	 */
	protected $atributos_permitida = array();

	public function __construct(){

	}

	/**
	 * Seta as tags permitidas
	 *
	 * @param  array || string
	 */
	public function settags_permitida($tags_permitida){

		if (!is_array($tags_permitida)){
			$tags_permitida = array($tags_permitida);
		}

		foreach($tags_permitida as $index => $element){

			// Se a tag foi fornecida sem atributos
			if (is_int($index) && is_string($element)){

				// localiza o nome da tag
				$tagName = strtolower($element);

				// Armazen a tag sem os atributos
				$this->tags_permitida[$tagName] = array();
			}

			// Caso contrário, se uma tag foi fornecida com atributo
			elseif(is_string($index) && (is_array($element) || is_string($element))){

				// Localiza o nome da tag
				$tagName = strtolower($index);

				// Localiza o/s atributo
				if(is_string($element)){
					$element = array($element);
				}

				// Armazena a tag tag com atributo
				$this->tags_permitida[$tagName] = array();

				foreach($element as $attribute){

					if(is_string($attribute)){

						// Localiza o nome do atributo
						$attributeName = strtolower($attribute);
						$this->tags_permitida[$tagName][$attributeName] = null;
					}
				}
			}
		}

		return $this;
	}

	/**
	 * Seta os atributos permitidos
	 *
	 * @param  array || string $atributos_permitida
	 */
	public function setatributos_permitida($atributos_permitida){

		if (!is_array($atributos_permitida)){
			$atributos_permitida = array($atributos_permitida);
		}

		// Armazena cada atributo como permitido
		foreach ($atributos_permitida as $attribute){

			if(is_string($attribute)){

				// Localiza o nome do atributo
				$attributeName = strtolower($attribute);
				$this->atributos_permitida[$attributeName] = null;
			}
		}

		return $this;
	}

	/**
	 * @param  string $value
	 * @return string
	 */
	public function strip_tags($value, $tags_permitida = false, $tagsAtribute = false){

		// Caso seja informado na função as tags permitidas.
		if($tags_permitida !== false){
			$this->settags_permitida($tags_permitida);
		}

		// Caso seja informado na função os atributos permitidas.
		if($tagsAtribute !== false){
			$this->setatributos_permitida($tagsAtribute);
		}

		$value = (string) $value;

		// Primeiro, remover os comentários HTML
		while(strpos($value, '<!--') !== false){
			$pos   = strrpos($value, '<!--');
			$start = substr($value, 0, $pos);
			$value = substr($value, $pos);

			// Se não houver tag de comentário fechada (-->), remove todo o texto
			if(!preg_match('/--\s*>/s', $value)){

				$value = '';

			}else{

				$value = preg_replace('/<(?:!(?:--[\s\S]*?--\s*)?(>))/s', '',  $value);
			}

			$value = $start.$value;
		}

		$dataFiltered = '';

		preg_match_all('/([^<]*)(<?[^>]*>?)/', (string) $value, $matches);

		foreach($matches[1] as $index => $preTag){

			// Se a pre-tag não for vazia, terirar todos os caracteres ">" dele
			if(strlen($preTag)){
				$preTag = str_replace('>', '', $preTag);
			}

			// Se houver uma tag com essa correspondência
			$tag = $matches[2][$index];

			if(strlen($tag)){

				$tagFiltered = $this->_filterTag($tag);

			}else{

				$tagFiltered = '';
			}

			// Adiciona a pre-tag filtrada e tag filtrada ao buffer
			$dataFiltered .= $preTag . $tagFiltered;
		}

		// Retorna os dados filtrados
		return $dataFiltered;
	}

	/**
	 * Filtra uma única tag nas configurações atuais
	 *
	 * @param  string $tag
	 * @return string
	 */
	protected function _filterTag($tag){

		$isMatch = preg_match('~(</?)(\w*)((/(?!>)|[^/>])*)(/?>)~', $tag, $matches);

		// Se não ocorrer o match com a Tag, remove a tag 
		if(!$isMatch){
			return '';
		}

		$tagStart      = $matches[1];
		$tagName       = strtolower($matches[2]);
		$tagAttributes = $matches[3];
		$tagEnd        = $matches[5];

		// Se a tag não for uma tag permitida, remove-a!
		if(!isset($this->tags_permitida[$tagName])){
			return '';
		}

		// Remove os espaços em brancos no início e no fim da string
		$tagAttributes = trim($tagAttributes);

		// Se não houver caracter em branco
		if(strlen($tagAttributes)){

			preg_match_all('/([\w-]+)\s*=\s*(?:(")(.*?)"|(\')(.*?)\')/s', $tagAttributes, $matches);

			$tagAttributes = '';

			foreach($matches[1] as $index => $attributeName){

				$attributeName      = strtolower($attributeName);
				$attributeDelimiter = empty($matches[2][$index]) ? $matches[4][$index] : $matches[2][$index];
				$attributeValue     = empty($matches[3][$index]) ? $matches[5][$index] : $matches[3][$index];

				// Se o atributo não for permitido, remova-o!
				if(!array_key_exists($attributeName, $this->tags_permitida[$tagName]) && !array_key_exists($attributeName, $this->atributos_permitida)){
					continue;
				}

				// Adiciona o atributo na variável
				$tagAttributes .= " $attributeName=".$attributeDelimiter.$attributeValue.$attributeDelimiter;
			}
		}

		// Reconstruir tags que termina com />
		if(strpos($tagEnd, '/') !== false){
			$tagEnd = " $tagEnd";
		}

		// Retorna a Tag filtrada
		return $tagStart.$tagName.$tagAttributes.$tagEnd;
	}
}