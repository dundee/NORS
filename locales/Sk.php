<?php

/**
* Locale_Sk
*
* @author Patrik Rosa <patrik.rosa@bigweb.sk>
* @copyright Patrik Rosa <patrik.rosa@bigweb.sk>
* @license http://www.opensource.org/licenses/gpl-license.php
* @package Core
*/

/**
* Locale_Sk
*
* @author Patrik Rosa <patrik.rosa@bigweb.sk>
* @package Core
*/
class Locale_Sk extends Core_Locale
{
	public $data = array(

		/* ============ administrácia ============ */

		//              Odpovede systému
		'DB_connection_failed'      => 'Pripojenie k databáze sa nepodarilo',
		'DB_query_failed'           => 'Prevedenie SQL dotazu sa nepodarilo',
		'wrong_password'            => 'Zadané heslo nie je správne',
		'user_not_exists'           => 'Zadaný užívateľ neexistuje',
		'we_are_very_sorry'         => 'Ospravedlňujeme sa. Vami požadovaná stránka nie je k dispozícii.',
		'out_of_order'              => 'Ospravedlňujeme sa. Aplikácia je z dôvodu údržby pozastavená.',
		'not_enough_rights'         => 'Pre túto akciu nemáte oprávnenie.',

		//              Prihlásenie
		'username'                  => 'Užívateľské meno',
		'password'                  => 'Heslo',
		'login'                     => 'Prihlásenie',
		'log_in'                    => 'Prihlásiť sa',
		'log_out'                   => 'Odhlásiť sa',

		'title'                     => 'názov',
		'saved'                     => 'uložené',

		//              Panel užívateľa
		'logged_in'                 => 'Prihlásený',
		'backup_db'                 => 'Zálohovať databázu',
		'show_web'                  => 'Zobraziť web',

		//              Záhlavie
		'gen_time'                  => 'Vygenerované za',
		'seconds'                   => 'sekund',
		'yes'                       => 'áno',
		'no'                        => 'nie',
		'memory'                    => 'pamäť',
		'included_files'            => 'vložených súborov',
		'sql_queries'               => 'dotazov na databázu',
		'stop_ie'                   => 'Používate nevyhovujúci prehliadač. Doporučujeme <a href="http://www.opera.com/download/">Operu</a> alebo <a href="http://www.mozilla-europe.org/cs/products/firefox/">Firefox</a>',

		//             Navigácia administrácie
		'homepage'                  => 'Úvod',
		'content'                   => 'Obsah',
		'news'                      => 'Novinky',
		'posts'                     => 'Články',
		'pages'                     => 'Stránky',
		'categories'               => 'Rubriky',
		'galleries'                 => 'Galérie',
		'anquettes'                 => 'Ankety',
		'citates'                   => 'Citáty',
		'comments'                  => 'Komentáre',
		'users'                     => 'Uživatelia',
		'groups'                    => 'Skupiny',
		'settings'                  => 'Nastavenia',

		//                Výpisy
		'filter'                    => 'Filtrovať',
		'add'                       => 'Pridať',
		'tree'                      => 'Strom',
		'dump'                      => 'Export',
		'action'                    => 'Akcia',
		'open'                      => 'Otvoriť',
		'edit'                      => 'Zmeniť',
		'activate'                  => 'Zapnúť',
		'deactivate'                => 'Vypnúť',
		'delete'                    => 'Vymazať',
		'really_delete'             => 'Skutočne vymazať',
		'id'                        => 'ID',
		'author'                    => 'Autor',
		'num_of_comments'           => 'Počet komentárov',
		'num_of_visits'             => 'Počet návštev',
		'karma'                     => 'Karma',
		'actions'                   => 'Akcia',
		'next'                      => 'ďalší',
		'previous'                  => 'predchádzajúci',


		//                Formuláre
		'name'                      => 'Názov',
		'send_form'                 => 'Odoslat',
		'post'                      => 'Článok',
		'page'                      => 'Stránka',
		'pub_date'                  => 'Publikované dňa',
		'category'                 => 'Rubrika',
		'perex'                     => 'Úvodná časť',
		'date'                      => 'Dátum',
		'active'                    => 'Aktívny',
		'name_of_web'               => 'Názov webu',
		'sucessfully_saved'         => 'Uloženie dát prebehlo v poriadku...',
		'no_items'                  => 'Žiadne položky',
		'save'                      => 'Uložiť',
		'save_and_continue'         => 'Uložiť a pokračovať',
		'save_file'                 => 'Uložiť súbor',
		'photo'                     => 'Obrázok',
		'label'                     => 'Označenie',
		'logging'                   => 'Prihlásenie',
		'password'                  => 'Heslo',
		'password_again'            => 'Heslo znovu',
		'text'                      => 'Text',
		'user'                      => 'Užívateľ',
		'group'                     => 'Skupina',
		'fullname'                  => 'Celé meno',
		'file'                      => 'súbor',
		'phone'                     => 'Telefón',
		'email'                     => 'E-mail',
		'created'                   => 'Vytvorený',
		'group'                     => 'Skupina',
		'exp_date'                  => 'Koniec platnosti',
		'basic_settings'            => 'Základné nastavenie',
		'description'               => 'Popis',
		'keywords'                  => 'Kľúčové slová',
		'link'                      => 'Odkaz',
		'position'                  => 'Pozícia',
		'basic'                     => 'Základné',
		'advanced'                  => 'Pokročilé',
		'comment'                   => 'Komentár',

		//             Práva
		'category_list'            => 'Výpis rubrík',
		'category_edit'            => 'Editácia rubrík',
		'category_del'             => 'Vymazanie rubrík',
		'post_list'                 => 'Výpis článkov',
		'post_edit'                 => 'Editácia článkov',
		'post_del'                  => 'Vymazanie článkov',
		'page_list'                 => 'Výpis stránok',
		'page_edit'                 => 'Editácia stránok',
		'page_del'                  => 'Vymazaníe stránok',
		'user_list'                 => 'Výpis užívateľov',
		'user_edit'                 => 'Editácia užívateľov',
		'user_del'                  => 'Vymazanie užívateľov',
		'group_list'                => 'Výpis skupín',
		'group_edit'                => 'Editácia skupín',
		'group_del'                 => 'Vymazanie skupín',
		'basic_list'                => 'Základné nastavenie',
		'advanced_list'             => 'Pokročilé nastavenie',

		/* ============ front-end ============ */

		//instalation
		'installation'              => 'Inštalácia',
		'database'                  => 'Databáza',
		'new_user'                  => 'Nový užívateľ',
		'host'                      => 'Server',
		'table_prefix'              => 'Prefix tabuliek',
		'adress_of_database_server' => 'Adresa databázového serveru',
		'name_of_database_user'     => 'Meno užívateľa databáze',
		'password_of_database_user' => 'Heslo užívateľa databáze',
		'name_of_database'          => 'Názov databáze',
		'prefix_of_nors_tables'     => 'Prefix tabuliek NORSu',
		'name_of_new_nors_user'     => 'Meno nového užívateľa',
		'password_of_new_nors_user' => 'Heslo nového užívateľa',
		'wrong_db_user'             => 'Zlý názov užívateľa alebo heslo',
		'wrong_db_name'             => 'Zlý názov databáze',
		'environment check'         => 'Kontrola prostredia',
		'directory'                 => 'Zložka',
		'needs to be writable by anyone' => 'potrebujete právo zápisu pre všetky',
		'Pleae repair errors and refresh the page' => 'Prosím opravte chyby a načítajte stránku znovu',

		//import
		'File db.php from "library" directory in NORS 3' => 'Súbor db.php zo zložky "library" v NORS 3',
		'import'                    => 'Import',
		'from'                      => 'z',

		'jump_to_navigation'        => 'preskočiť na navigáciu',
		'replied_by'                => 'Na tento komentár odpovedal',
		'reply'                     => 'Odpovedať',
		'other'                     => 'Ostatný',
		'seen'                      => 'zobrazený',
		'source'                    => 'zdroj',
		'administration'            => 'Administrácia',

		);
	public function decodeDate($ymd_his)
	{
		$text_obj = new Core_Text($ymd_his);
		return date("d.m.Y",$text_obj->dateToTimeStamp());
	}

	public function decodeDatetime($ymd_his)
	{
		$text = new Core_Text($ymd_his);
		return date("d.m.Y v H:i:s",$text->dateToTimeStamp());
	}

	public function encodeDatetime($dmy_his)
	{
		if (!$dmy_his) return "0000-00-00 00:00:00";
		return $dmy_his;

		list($date, $time) = explode(' ', $dmy_his);
		list($d,$m,$y) = explode('.', $date);
		list($h,$i,$s) = explode(':', $time);
		return "$y-$m-$d $h:$i:$s";
	}

	public function encodeDate($dmy)
	{
		if (!$dmy) return "0000-00-00";

		list($d,$m,$y) = explode('.', $dmy);
		return "$y-$m-$d";
	}
}
