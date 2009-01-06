<?php

/**
* Locale_Cs
*
* @author Daniel Milde <daniel@milde.cz>
* @copyright Daniel Milde <daniel@milde.cz>
* @license http://www.opensource.org/licenses/gpl-license.php
* @package Core
*/

/**
* Locale_Cs
*
* @author Daniel Milde <daniel@milde.cz>
* @package Core
*/
class Locale_Cs extends Core_Locale
{
	public $data = array(

		/* ============ administrace ============ */

		//              Odpovedi systemu
		'DB_connection_failed'      => 'Připojení k databázi se nezdařilo',
		'DB_query_failed'           => 'Provedení SQL dotazu se nezdařilo',
		'wrong_password'            => 'Zadané heslo není správné',
		'user_not_exists'           => 'Zadaný uživatel neexistuje',
		'we_are_very_sorry'         => 'Omlouváme se. Vámi požadovaná stránka není k dispozici.',
		'out_of_order'              => 'Omlouváme se. Aplikace je z důvodu údržby pozastavena.',
		'not_enough_rights'         => 'Pro tuto akci nemáte oprávnění.',

		//              Prihlaseni
		'username'                  => 'Uživatelské jméno',
		'password'                  => 'Heslo',
		'login'                     => 'Přihlášení',
		'log_in'                    => 'Přihlásit',
		'log_out'                   => 'Odhlásit se',

		'title'                     => 'titulek',
		'saved'                     => 'uloženo',

		//              Panel uzivatele
		'logged_in'                 => 'Přihlášen',
		'backup_db'                 => 'Zálohovat databázi',
		'show_web'                  => 'Zobrazit web',

		//              Zapati
		'gen_time'                  => 'Vygenerováno za',
		'seconds'                   => 'sekund',
		'yes'                       => 'ano',
		'no'                        => 'ne',
		'memory'                    => 'paměť',
		'included_files'            => 'vložených souborů',
		'sql_queries'               => 'dotazů na databázi',
		'stop_ie'                   => 'Používáte nevyhovující prohlížeč. Doporučujeme <a href="http://www.opera.com/download/">Operu</a> nebo <a href="http://www.mozilla-europe.org/cs/products/firefox/">Firefox</a>',

		//             Navigace administrace
		'homepage'                  => 'Úvod',
		'content'                   => 'Obsah',
		'posts'                     => 'Články',
		'pages'                     => 'Stránky',
		'cathegories'               => 'Rubriky',
		'galleries'                 => 'Galerie',
		'anquettes'                 => 'Ankety',
		'citates'                   => 'Citáty',
		'comments'                  => 'Komentáře',
		'users'                     => 'Uživatelé',
		'groups'                    => 'Role',
		'settings'                  => 'Nastavení',

		//                Vypisy
		'filter'                    => 'Filtrovat',
		'add'                       => 'Přidat',
		'tree'                      => 'Strom',
		'dump'                      => 'Export',
		'action'                    => 'Akce',
		'open'                      => 'Otevřít',
		'edit'                      => 'Změnit',
		'activate'                  => 'Zapnout',
		'deactivate'                => 'Vypnout',
		'delete'                    => 'Smazat',
		'really_delete'             => 'Opravdu smazat',
		'id'                        => 'ID',
		'author'                    => 'Autor',
		'num_of_comments'           => 'Počet komentářů',
		'num_of_visits'             => 'Počet shlédnutí',
		'karma'                     => 'Karma',
		'actions'                   => 'Akce',


		//                Formulare
		'name'                      => 'Název',
		'send_form'                 => 'Odeslat',
		'post'                      => 'Článek',
		'page'                      => 'Stránka',
		'pub_date'                  => 'Publikováno dne',
		'cathegory'                 => 'Rubrika',
		'perex'                     => 'Perex',
		'date'                      => 'Datum',
		'active'                    => 'Aktivní',
		'name_of_web'               => 'Název webu',
		'sucessfully_saved'         => 'Ukládání dat proběhlo v pořádku...',
		'no_items'                  => 'Žádné položky',
		'save'                      => 'Uložit',
		'save_and_continue'         => 'Uložit a pokračovat',
		'save_file'                 => 'Uložit soubor',
		'photo'                     => 'Obrázek',
		'label'                     => 'Popisek',
		'logging'                   => 'Přihlášení',
		'password'                  => 'Heslo',
		'password_again'            => 'Heslo znovu',
		'text'                      => 'Text',
		'user'                      => 'Uživatel',
		'group'                     => 'Skupina',
		'fullname'                  => 'Celé jméno',
		'file'                      => 'soubor',
		'phone'                     => 'Telefon',
		'email'                     => 'E-mail',
		'created'                   => 'Vytvořen',
		'group'                     => 'Role',
		'exp_date'                  => 'Konec platnosti',
		'basic_settings'            => 'Základní nastavení',
		'description'               => 'Popis',
		'keywords'                  => 'Klíčová slova',
		'link'                      => 'Odkaz',
		'position'                  => 'Pozice',
		'basic'                     => 'Základní',
		'advanced'                  => 'Pokročilé',
		'comment'                   => 'Komentář',

		//             Prava
		'cathegory_list'            => 'Výpis rubrik',
		'cathegory_edit'            => 'Editace rubrik',
		'cathegory_del'             => 'Mazání rubrik',
		'post_list'                 => 'Výpis článků',
		'post_edit'                 => 'Editace článků',
		'post_del'                  => 'Mazání článků',
		'page_list'                 => 'Výpis stránek',
		'page_edit'                 => 'Editace stránek',
		'page_del'                  => 'Mazání stránek',
		'user_list'                 => 'Výpis uživatelů',
		'user_edit'                 => 'Editace uživatelů',
		'user_del'                  => 'Mazání uživatelů',
		'group_list'                => 'Výpis rolí',
		'group_edit'                => 'Editace rolí',
		'group_del'                 => 'Mazání rolí',
		'basic_list'                => 'Základní nastavení',
		'advanced_list'             => 'Pokročilé nastavení',
		
		/* ============ front-end ============ */
		
		//instalation
		'installation'              => 'Instalace',
		'database'                  => 'Databáze',
		'new_user'                  => 'Nový uživatel',
		'host'                      => 'Server',
		'table_prefix'              => 'Prefix tabulek',
		'adress_of_database_server' => 'Adresa databázového serveru',
		'name_of_database_user'     => 'Jméno uživatele databáze',
		'password_of_database_user' => 'Heslo uživatele databáze',
		'name_of_database'          => 'Název databáze',
		'prefix_of_nors_tables'     => 'Prefix tabulek NORSu',
		'name_of_new_nors_user'     => 'Jméno nového uživatele',
		'password_of_new_nors_user' => 'Heslo nového uživatele',
		'wrong_db_user'             => 'Špatný název uživatele nebo heslo',
		'wrong_db_name'             => 'Špatný název databáze',

		//import
		'File db.php from "library" directory in NORS 3' => 'Soubor db.php ze složky "library" v NORS 3',
		'import'                    => 'Import',
		'from'                      => 'z',

		'jump_to_navigation'        => 'přeskočit na navigaci',
		'replied_by'                => 'Na tento komentář odpověděl',
		'reply'                     => 'Odpovědět',
		'other'                     => 'Ostatní',
		'seen'                      => 'zobrazen',
		'source'                    => 'zdroj',
		'administration'            => 'Administrace',

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
?>
