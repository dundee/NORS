<?php

if ($lang == 'Cs') {
	echo '<h1>NORS projekt</h1>';

	echo '<p>Cílem projektu bude vyvinout kompaktní redakční systém pro platformu PHP/MySQL.
Cílem projektu není konkurovat komplexním CMS, ale spíše se soustředit na uživatele,
jejichž požadavky žádný redakční systém zatím nesplňuje. Důraz bude kladen především na jednoduchost,
vysoký výkon, snadnou rozšiřitelnost, jednoduchou instalaci, striktní dodržování objektového přístupu a
softwarové architektury, přehledné uživatelské rozhraní a snadnou konfiguraci.</p>';
} else {
	echo '<h1>NORS project</h1>';

	echo '<p>Goal of the project is to develop compact CMS for PHP/MySQL platform.
Project should not compete complex CMSs, rather concentrate on users
whose requests satisfies no current CMS. Accent will be put firstly on simplicity,
high performance, extensibility, easy installation, strict adherence to object paradigm and
software architecture, well-arranged user interface and easy configuration.</p>';

}

include(APP_PATH.'/tpl/layout/menu.tpl.php');
