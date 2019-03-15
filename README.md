# Remover Tags/script HTML em PHP


## PT-BR

- Modo de uso SIMPLES;

// texto exemplo
$string = '\<p id="paragrafo1" class="paragrafo">O importante é oque mais importa! \<br /> No final tudo é 0 e 1.</p>';

// Instancia a classe
$strip = new StripTags;

// coloca a $string na função para remover as tags
## $nova_string = $strip->strip_tags($string);

-- A saída vai ser
  O importante é oque mais importa!  No final tudo é 0 e 1.
 
 
 
###### para permitir tags, vamos permitir o \<p>
## $nova_string = $strip->strip_tags($string, 'p');
-- A saída vai ser
  \<p>O importante é oque mais importa!  No final tudo é 0 e 1.</p>

  // O segundo parâmetro pode ser string ou array usage..
  $tags_permitidas = array('div', 'a', 'p');
##  $nova_string = $strip->strip_tags($string, $tags_permitidas);



###### para permitir atributos, vamos permitir o atributo class
## $nova_string = $strip->strip_tags($string, 'p', 'class');
-- A saída vai ser
  \<p class="paragrafo1">O importante é oque mais importa!  No final tudo é 0 e 1.</p>

  // O terceiro parâmetro pode ser string ou array usage..
  $tags_permitidas = array('div', 'a', 'p');
  $atributos_permitidos = array('class', 'id', 'data-nome');
##  $nova_string = $strip->strip_tags($string, $tags_permitidas, $atributos_permitidos);





## PT-EN

-- mode usage simple

// string exemple
$string = '<p id="paragrafo1" class="paragrafo">The important is what matters most \<br /> Not everything is 0 and 1</p>';

// Instance the class (invok)
$strip = new StripTags;

// put the string in the function of remove the tags
## $new_string = $strip->strip_tags($string);

-- output will be
 The important is what matters most Not everything is 0 and 1.


###### To allowed tags, let's allowed the \<p>
## $new_string = $strip->strip_tags($string, 'p');
-- output will be
  \<p>The important is what matters most Not everything is 0 and 1.</p>

  // the second parameter can be string or array, usage
  $tags_allowed = array('div', 'a', 'p');
##  $new_string = $strip->strip_tags($string, $tags_allowed);


###### To allowed attributes, let's allowed the attribute class
## $new_string = $strip->strip_tags($string, 'p', 'class');
-- output will be
  \<p class="paragrafo1">The important is what matters most Not everything is 0 and 1.</p>

  // the third parameter can be string or array, usage
  $tags_allowed = array('div', 'a', 'p');
  $attributes_allowed = array('class', 'id', 'data-name');
##  $new_string = $strip->strip_tags($string, $tags_allowed, $attributes_allowed);



-- ass: Matheus Maydana'

