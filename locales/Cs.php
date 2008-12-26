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
		'users'                     => 'Uživatelé',
		'groups'                    => 'Role',
		'settings'                  => 'Nastavení',

		'municipality'              => 'města a obce',
		'service'                   => 'služby',

		//                Vypisy
		'add'                       => 'Přidat',
		'tree'                      => 'Strom',
		'add_category'              => 'Přidat kategorii',
		'add_next_category'         => 'Přidat další kategorii',
		'add_post'                  => 'Přidat článek',
		'add_next_post'             => 'Přidat další článek',
		'add_text'                  => 'Přidat text',
		'add_next_text'             => 'Přidat další text',
		'add_user'                  => 'Přidat uživatele',
		'add_next_user'             => 'Přidat dalšího uživatele',
		'add_product'               => 'Přidat produkt',
		'add_next_product'          => 'Přidat další produkt',
		'add_group'                 => 'Přidat roli',
		'add_next_group'            => 'Přidat další roli',
		'dump'                      => 'Export',
		'category_tree'             => 'Strom kategorií',
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


		//                Formulare
		'name'                      => 'Název',
		'send_form'                 => 'Odeslat',
		'post'                      => 'Článek',
		'categories'                => 'Kategorie',
		'pub_date'                  => 'Publikováno dne',
		'category'                  => 'Kategorie',
		'perex'                     => 'Perex',
		'date'                      => 'Datum',
		'active'                    => 'Aktivní',
		'sucessfully_saved'         => 'Ukládání dat proběhlo v pořádku...',
		'root_category'             => 'Kořenová kategorie',
		'no_items'                  => 'Žádné položky',
		'save'                      => 'Uložit',
		'save_and_continue'         => 'Uložit a pokračovat',
		'save_file'                 => 'Uložit soubor',
		'photo'                     => 'Obrázek',
		'label'                     => 'Popisek',
		'logging'                   => 'Přihlášení',
		'password'                  => 'Heslo',
		'password_again'            => 'Heslo znovu',
		'search'                    => 'hledání',
		'text'                      => 'Text',
		'user'                      => 'Uživatel',
		'group'                     => 'Skupina',
		'fullname'                  => 'Celé jméno',
		'phone'                     => 'Telefon',
		'email'                     => 'E-mail',
		'created'                   => 'Vytvořen',
		'group'                     => 'Role',
		'services'                  => 'Nabízené služby',
		'exp_date'                  => 'Konec platnosti',
		'basic_settings'            => 'Základní nastavení',
		'name_of_web'               => 'Název webu',
		'description'               => 'Popis',
		'keywords'                  => 'Klíčová slova',

		//             Prava
		'post_list'                 => 'Výpis článků',
		'post_edit'                 => 'Editace článků',
		'post_del'                  => 'Mazání článků',
		'text_list'                 => 'Výpis texů',
		'text_edit'                 => 'Editace textů',
		'text_del'                  => 'Mazání textů',
		'category_list'             => 'Výpis kategorií',
		'category_edit'             => 'Editace kategorií',
		'category_del'              => 'Mazání kategorií',
		'user_list'                 => 'Výpis uživatelů',
		'user_edit'                 => 'Editace uživatelů',
		'user_del'                  => 'Mazání uživatelů',
		'group_list'                => 'Výpis rolí',
		'group_edit'                => 'Editace rolí',
		'group_del'                 => 'Mazání rolí',
		'settings_list'             => 'Výpis nastavení',
		'settings_edit'             => 'Editace nastavení',
		'settings_del'              => 'Mazání nastavení',

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
