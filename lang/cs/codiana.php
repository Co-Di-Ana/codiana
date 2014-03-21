<?php

// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.


/**
 * English strings for codiana
 *
 * You can have a rather longer description of the file as well,
 * if you like, and it can span multiple lines.
 *
 * @package    mod_codiana
 * @copyright  2011 Your Name
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined ('MOODLE_INTERNAL') || die();

$string['modulename']           = 'codiana';
$string['modulenameplural']     = 'codiana';
$string['modulename_help']      = 'CoDiAna je modul, který je schopen automatizovaně zpracovávat přijatá řešení, což jsou zdrojové kódy vytvořené studenty, na algoritmické úlohy a zároveň poskytnout výsledky a statistiky odevzdaných řešení a sledovat jejich náročnosti. Další činností systému je kontrola podobnosti přijatých řešení sloužící pro případné odhalení plagiátorství.';
$string['codiana']              = 'codiana';
$string['pluginadministration'] = 'CoDiAna administrace';
$string['pluginname']           = 'codiana';


// edit form
$string['name']                  = 'Jméno úlohy';
$string['mainfilename']          = 'Hlavní soubor úlohy';
$string['difficulty']            = 'Obtížnost';
$string['outputmethod']          = 'Metoda porovnávání výstupu';
$string['outputmethod:strict']   = 'Striktní porovnávání';
$string['outputmethod:tolerant'] = 'Tolerantní porovnávání';
$string['outputmethod:vague']    = 'Vágní porovnávání';
$string['grademethod']           = 'Metoda známkování';
$string['grademethod:first']     = 'První správné řešení';
$string['grademethod:last']      = 'Poslední správné řešení';
$string['grademethod:best']      = 'Nejlepší řešení';
$string['languages']             = 'Povolené programovací jazyky';
$string['timeopen']              = 'Dostupná od';
$string['timeclose']             = 'Dostupná do';
$string['maxusers']              = 'Maximum uživatelů';
$string['maxattempts']           = 'Maximum pokusů';
$string['limittime']             = 'Časový limit';
$string['limitmemory']           = 'Paměťový limit';
$string['solutionfile']          = 'Soubor s řešením';
$string['inputfile']             = 'Vstupní soubor';
$string['outputfile']            = 'Výstupní soubor';
$string['inputexample']          = 'Příklad vstupu';
$string['outputexample']         = 'Příklad výstupu';
$string['measurevalues']         = 'Měření hodnot';
$string['sourcefile']            = 'Řešení';
$string['solutionlanguage']      = 'Jazyk řešení';
$string['outputorsolutionfile']  = 'Výstup nebo řešení';

// edit form help
$string['name_help']          = 'Hlavní název úlohy';
$string['mainfilename_help']  = 'Název hlavního souboru úlohy. Pod tímto názvem budou studenti odevzdávat svá řešení. Musí začínat velkým písmenem a může ohsabovat pouze písmena, číslice a podtržítko. Například v jazyce Java se bude jedna o Hlavní třídu s tímto názvem.';
$string['difficulty_help']    = 'Obtížnost úlohy, kde 1 je nejlehčí a 5 nejtěžší.';
$string['outputmethod_help']  = 'Způsob porovnání výstupů, tj. jak přísné porovnání bude zvoleno. Striktní - znak po znaku. Tolerantní řádek po řádku (ignorují se volné řádky). Vágní token po tokenu (výstup se rozdělí na skupiny neprázdných znaků a ty se porovnávají).';
$string['grademethod_help']   = 'Způsob hodnocení řešení, tj. které řešení bude vybráno k oznámkování, když student pošle více řešení.';
$string['languages_help']     = 'Dovolené použité programovací jazyky.';
$string['timeopen_help']      = 'Od kdy bude úloha otevřena.';
$string['timeclose_help']     = 'Do kdy bude úloha otevřena.';
$string['maxusers_help']      = 'Maximum uživatelů řešícíh tuto úlohu.';
$string['maxattempts_help']   = 'Maximum pokusů uživatele.';
$string['limittime_help']     = 'Časové limity úlohy. První práh určuje, 100% časové ohodnocení. Druhý práh určuje 0% časové ohodnocení. Ohodnocení mezi prahy je lineární dopočítáno.';
$string['limitmemory_help']   = 'Paměťové limity úlohy. První práh určuje, 100% ohodnocení paměti. Druhý práh určuje 0% ohodnocení  paměti. Ohodnocení mezi prahy je lineární dopočítáno.';
$string['solutionfile_help']  = 'Soubor se správným řešení, které vygeneruje výstup. Podle tohoto výstupního souboru budou porovnávány výstupy řešení studentů.';
$string['inputfile_help']     = 'Vstupní soubor úlohy.';
$string['outputfile_help']    = 'Výstupní soubor úlohy.';
$string['inputexample_help']  = 'Ukázka toho, jaký může být vstup (zjednodušený vstup, na kterém je demostrováno chování úlohy).';
$string['outputexample_help'] = 'Ukázka toho, jaký musí být výstup (výstup, který bude vygenerován na základě uvedeného vstupu)';
// TODO add checkbox
$string['measurevalues_help']        = 'Systém automaticky detekuji časovou a paměťovou náročnost a vygeneruje výstup.';
$string['sourcefile_help']           = $string['solutionfile_help'];
$string['solutionlanguage_help']     = 'Specifikujte v programovacím jazyce je řešení.';
$string['outputorsolutionfile_help'] = 'Zvolte, zda se jedná o výstup nebo řešení';


// mod_form sections
$string['submitsolution:submit']          = 'Poslat';
$string['section:availability']           = 'Dostupnost';
$string['section:limits']                 = 'Limity';
$string['section:files']                  = 'Soubory';
$string['section:examples']               = 'Příklady vstupu a výstupu';
$string['section:results']                = 'Viditelnost';
$string['section:inputfilesection']       = 'Vstupní soubor';
$string['section:outputfilesection']      = 'Výstupní soubor nebo řešení';
$string['section:inputfilesection_help']  = 'Je nutné specifikovat nebo generovat vstupní soubor';
$string['section:outputfilesection_help'] = 'Je nutné specifikovat nebo vygenerovat výspuní soubor';


// settings
$string['setting:storage']     = 'Úložiště';
$string['setting:limittime']   = 'Maximální doba běhu programu';
$string['setting:limitmemory'] = 'Maximální paměťová náročnost programu';
$string['setting:islocal']     = 'Je úložiště lokální';
$string['setting:storagepath'] = 'Cesta k úložišti';
$string['setting:sshusername'] = 'Jméno (SSH)';
$string['setting:sshpassword'] = 'Heslo (SSH)';
$string['setting:javaip']      = 'Java-side IP';
$string['setting:javaport']    = 'Java-side port';
$string['setting:javamessage'] = 'Fráze pro Java-side aplikaci';


$string['setting:storage_desc']     = 'Nastavení úložistě';
$string['setting:limittime_desc']   = 'Určete maximální dobu běhu programu v sekundách';
$string['setting:limitmemory_desc'] = 'Určete maximální paměťovou náročnost programu v B';
$string['setting:islocal_desc']     = 'Určete, zda je úložiště lokální (zaškrtněte pokud ano)';
$string['setting:storagepath_desc'] = 'Určete, v jaké složce bude vytvořené úložiště';
$string['setting:sshusername_desc'] = 'Pokud je úložiště vzdálené, zadejte uživatelské jméno pro SSH, jinak nechte pole prázdné';
$string['setting:sshpassword_desc'] = 'Pokud je úložiště vzdálené, zadejte uživatelské heslo pro SSH, jinak nechte pole prázdné';
$string['setting:javaip_desc']      = 'IP adresa, na které běží Java-side aplikace';
$string['setting:javaport_desc']    = 'Číslo portu, na kterém naslouchá Java-side aplikace';
$string['setting:javamessage_desc'] = 'Fráze, která spustí kontrolu programů';

// states
$string['state:process_aborted']     = 'Kontrola přerušena';
$string['state:other_error']         = 'Jiná chyba';
$string['state:waiting_to_process']  = 'Čekání na zpracování';
$string['state:code_dangerous']      = 'Nebezpečný kód';
$string['state:code_invalid']        = 'Nesprávný kód';
$string['state:code_valid']          = 'Validní kód';
$string['state:comiplation_error']   = 'Chyba při kompilaci';
$string['state:comiplation_timeout'] = 'Dosažen časový limit kompilace';
$string['state:comiplation_ok']      = 'Compilace v pořádku';
$string['state:execution_error']     = 'Chyba spuštění';
$string['state:execution_timeout']   = 'Dosažen časový limit';
$string['state:execution_ok']        = 'Spuštění v pořádku';
$string['state:output_error']        = 'Chybný výstup';
$string['state:time_error']          = 'Dosažen časový limit';
$string['state:memory_error']        = 'Dosažen paměťový limit';
$string['state:measurement_ok']      = 'Měření v pořádku';




// error
$string['error:filedoesnotexists']               = "Soubor neexistuje!";
$string['error:youcannotdownloadthisfile']       = 'Nelze stáhnout soubor!';
$string['error:youcannotperformplagiarismcheck'] = 'Nemáte opravnění pro spuštění kontroly plagiátorství!';
$string['error:solutiondoesnotexists']           = 'Řešení neexistuje!';
$string['error:unsupportedfiletypex']            = 'Nepodporovaná přípona \'%s\'';


// capabilities
$string['addinstance']     = 'Vytvořit novou CoDiAna úlohu';
$string['createinputfile'] = 'Nahrát vstupní soubor';
// TODO jaké?
$string['manager']             = 'Mít vyšší oprávnění';
$string['managetaskfiles']     = 'Manage task files';
$string['submitprotosolution'] = 'Odevzdat řešení za účelem měření';
$string['submitsolution']      = 'Odevzdat řešení ';
$string['viewmyattempts']      = 'Shlédout vlastní výsledky';
$string['viewresults']         = 'Shlédout všechny výsledky';


$string['input_file']  = 'Vstupní soubor';
$string['output_file'] = 'Výstupní soubor';


$string['downloadoutput'] = 'Stáhnout současný výstupní soubor';
$string['downloadinput']  = 'Stáhnout současný vstupní soubor';


$string['form:specify_atleast_one_file'] = 'Je nutné specifikovat alespoň jeden soubor';



$string['managefiles:outputorsolution'] = 'Výstupní nebo řešení';
$string['managefiles:output']           = 'Výstupní soubor';
$string['managefiles:solution']         = 'Řešení';


$string['message:protoinsertedintoqueue']         = 'Řešení vloženo do fronty, měření budou automaticky vyplněna v nastavení úlohy';
$string['message:errorinsertingintoqueue']        = 'Chyba při vkládání řešení do fronty';
$string['message:noactionperformed']              = 'Nebyly provedeny žádné změny!';
$string['message:cannotdeleteinputfile']          = 'Nelze smazat výstupní soubor';
$string['message:cannotcreateinputfile']          = 'Nelze vytvořit výstupní soubor';
$string['message:inputsaved']                     = 'Vstupní soubor uložen';
$string['message:inputgenerated']                 = 'Vstupní soubor vygenerován';
$string['message:errorgeneratinginput']           = 'Error while generating input file';
$string['message:cannotdeleteoutputfile']         = 'Nelze smazat vstupní soubor';
$string['message:cannotcreateoutputfile']         = 'Nelze vytvořit vstupní soubor';
$string['message:outputsaved']                    = 'Výstupní soubor uložen';
$string['message:fileistoobig']                   = 'Vygenerovaný soubor je příliš velký!';
$string['message:filegenerated']                  = 'Vstupní soubor vygenerován!';
$string['message:cannotconnecttoserver']          = 'Nelze se připojit k serveru!';
$string['message:cannotsendmessagetoserver']      = 'Nelze poslat zprávu na server!';
$string['message:taskdeactivated']                = 'Úloha deaktivována!';
$string['message:taskactivated']                  = 'Úloha aktivována!';
$string['message:taskplagcheckinserted']          = 'Kontrola plagiátorství úlohy vložena do fronty zpracování';
$string['message:soluitonplagcheckinserted']      = 'Kontrola plagiátorství řešení vložena do fronty zpracování';
$string['message:nochangespermormed']             = 'Nebyly provedeny žádné změny';
$string['message:xchangesperformed']              = 'Změny provedeny (celkem: %d)';
$string['message:attempt_edited']                 = 'Pokus upraven';
$string['message:attempt_edit_failed']            = 'Úprava se nezdařila!';
$string['message:generate_input_x']               = 'Vstupní soubor lze generovat %s';
$string['message:maximum_no_of_attempts_reached'] = 'Dosáhli jste maximálního počtu pokusů!';
$string['message:no_more_attempts_x_of_x']        = 'Žádné další pokusy (%d / %d)';
$string['message:attempt_x_from_x']               = 'Pokus %d / %s';
$string['message:uploaded_x_attempt']             = 'Nahrán %d. pokus';
$string['message:no_grade_yet']                   = 'Doposud žádná známka';
$string['message:x_similar_solutions_x']          = '%d podobných řešení (%d&nbsp;%%)';
$string['message:no_description']                 = 'Doposud žádný popis';
$string['message:checked_x']                      = 'Zkontrolováno %s';

$string['date:justnow']       = 'před okamžikem';
$string['date:x_minutes_ago'] = ' před %d minutami';
$string['date:hour_ago']      = 'před hodinou';
$string['date:x_hours_ago']   = ' před %d hodinami';
$string['date:yesterday']     = 'včera';
$string['date:x_days_ago']    = ' před %d dny';

$string['date:in_few_minutes']  = 'za pár okamžiků';
$string['date:in_x_minutes']    = 'za %d minut';
$string['date:in_one_hour']     = 'za hodinu';
$string['date:in_x_hours']      = 'za %d hodin';
$string['date:tomorow']         = 'zítra';
$string['date:x_days_from_now'] = 'za %d dní';

$string['menu:viewallresults'] = 'Zobrazit všechny výsledky';
$string['menu:generateinput']  = 'Vygenerovat vstupní soubor';
$string['menu:managefiles']    = 'Spravovat soubory';
$string['menu:viewmyresults']  = 'Zobrazit výsledky';
$string['menu:submitsolution'] = 'Odevzdat řešení';
$string['menu:showgrades']     = 'Zobrazit hodnocení';
$string['menu:managaegrades']  = 'Spravovat známky';
$string['menu:activatetask']   = 'Aktivovat úlohu';
$string['menu:deactivatetask'] = 'Deaktivovat úlohu';


$string['warning:iofilesmissing']            = 'Vstupní a výstupní soubory chybí';
$string['warning:ifilesmissing']             = 'Vstupní soubor chybí!';
$string['warning:ofilesmissing']             = 'Výstupní soubor chybí!';
$string['warning:submiting_will_override']   = 'Nahrání nového souboru přepíše staré soubory';
$string['warning:specify_x_before_activate'] = 'Je nutné specifikovat %s, aby mohla být úloha aktivována!';
$string['warning:abortedsolution']           = 'Odevzdáním nového řešení přerušíte kontrolu minulého řešení!';
$string['warning:similarityx']               = 'Podobnost %1.2f!';
// TODO class package?
$string['warning:main_filename_x']  = 'Odevzdané řešení musí obsahovat soubor %s se správnou příponou';
$string['warning:task_no_start']    = 'Úloha nemá specifikovaný začátek';
$string['warning:task_no_end']      = 'Úloha nemá specifikovaný konec';
$string['warning:no_limittime_set'] = 'Nenastaven žádný časový limit';
$string['warning:no_grade_attempt'] = 'Nemáte žádný pokus vhodný k oznámkování';
$string['warning:no_results']       = 'Žádné výsledky';
$string['warning:no_grade']         = 'Žádná známka';
$string['warning:plags_result']     = 'Výsledky plagiátorství';
$string['warning:languages_notice'] = 'Upozornění';


// TODO ještě jsem to nespustil a je to tam! :D
$string['plagstate:no_dupes_found']         = 'Nenalezeny žádné duplikáty';
$string['plagstate:dupes_found']            = 'Duplikáty detekovány';
$string['plagstate:check_not_yet_executed'] = 'Doposud neprovedena';
$string['plagstate:check_aborted']          = 'Kontrola přerušena';
$string['plagstate:check_in_progress']      = 'Probíhá zpracování';

$string['taskstate:active']     = 'Aktivována';
$string['taskstate:not_active'] = 'Deaktivována';

$string['taskstate:task_not_active']  = 'Úloha není aktivována';
$string['taskstate:task_not_started'] = 'Úloha nezačala';
$string['taskstate:task_has_ended']   = 'Úloha skončila';

$string['taskdetail:simple']  = 'jeden soubor';
$string['taskdetail:complex'] = 'více souborů';

$string['title:managefilex']         = 'Správa souborů (%s)';
$string['title:edit_attempt_from_x'] = 'Úprava pokusu od  %s';
$string['title:edit_attempt']        = 'Úprava pokusu';
$string['title:user_grade']          = 'Známka';
$string['title:task_detail']         = 'Detaily úlohy';
$string['title:task_advanced']       = 'Pokročilé';
$string['title:task_limits']         = 'Limity';
$string['title:task_status']         = 'Stav';
$string['title:general_info']        = 'Obecné informace';
$string['title:grades']              = 'Známky';
$string['title:grading_attempt']     = 'Známkovaný pokus';
$string['title:grading_attempts']    = 'Známkované pokusy';
$string['title:all_attempts']        = 'Všechny pokusy';
$string['title:plags_result']        = 'Výsledky plagiátorství';
$string['title:task_x']              = 'Úloha \'%s\'';
$string['title:task_x_title']        = 'Úloha \'%s\' - %s';
$string['title:description']         = 'Popis';
$string['title:generate_input']      = 'Genrování vstupu';
$string['title:grading']             = 'Známkování';
$string['title:manage_files']        = 'Správa souborů';
$string['title:submit_solution']     = 'odevzdání řešení';
$string['title:view_my_attempts']    = 'Mé pokusy';
$string['title:view_all_attempts']   = 'Výsledky';
$string['title:stats']               = 'Statistiky';
$string['title:state_stat']          = 'Celkové Statistiky';
$string['title:resultfinal_stat']    = 'Finální výsledky';
$string['title:time_stat']           = 'Časové Statistiky';
$string['title:memory_stat']         = 'Paměťové Statistiky';


$string['settings:open_solver']   = 'Aktivní úloha, pro řešitele';
$string['settings:close_solver']  = 'Úloha skončila, pro řešitele';
$string['settings:active_others'] = 'Aktivní úloha, pro ostatní';
$string['settings:close_others']  = 'Úloha skončila, pro ostatní';

$string['settings:open_solver_desc']   = 'Když je úloha aktivní, nastavení pro řešitele';
$string['settings:close_solver_desc']  = 'Když úloha skončila, nastavení pro řešitele';
$string['settings:active_others_desc'] = 'Když je úloha aktivní, nastavení pro ostatní';
$string['settings:close_others_desc']  = 'Když úloha skončila, nastavení pro ostatní';


$string['btn:edit']                     = 'Upravit';
$string['btn:cancel']                   = 'Zrušit';
$string['btn:back']                     = 'Zpět';
$string['btn:check']                    = 'Zkontrolovat';
$string['btn:check_again']              = 'Znovu zkontrolovat';
$string['btn:here']                     = 'zde';
$string['btn:show_all_results']         = 'Zobrazit všechny pokusy';
$string['btn:show_grades_results']      = 'Zobrazit známkované pokusy';
$string['btn:detect_dupes']             = 'Detekovat duplikáty';
$string['btn:detect_dupes_in_progress'] = 'Probíhá kontrola detekování duplikátů';
$string['btn:detect_dupes_again']       = 'Detekovat duplikáty znovu';
$string['btn:no_dupes_found_again']     = 'Žádné duplikáty nenalezeny, zkontrolovat znovu?';
$string['btn:download']                 = 'stáhnout';

$string['col:id']              = 'ID';
$string['col:timemodified']    = 'Upraveno';
$string['col:timecreated']     = 'Vytvořeno';
$string['col:plagscheckstate'] = 'Stav plagiátorství';
$string['col:maxattempts']     = $string['maxattempts'];
$string['col:maxusers']        = $string['maxusers'];
$string['col:limittime']       = $string['limittime'];
$string['col:limitmemory']     = $string['limitmemory'];
$string['col:state']           = 'Stav';
$string['col:timeopen']        = $string['timeopen'];
$string['col:timeclose']       = $string['timeclose'];
$string['col:timesent']        = 'Čas odeslání';
$string['col:languages']       = 'Programovací jazyky';
$string['col:difficulty']      = 'Obtížnost';
$string['col:outputmethod']    = $string['outputmethod'];
$string['col:grademethod']     = $string['grademethod'];

$string['col:suggested_grade'] = 'Navržená známka';
$string['col:current_grade']   = 'Současná známka';
$string['col:finalresult']     = 'Výsledek řešení';
$string['col:username']        = 'Uživatel';

$string['col:str_long_grade'] = $string['col:current_grade'];
$string['col:dategraded']     = 'Známkováno';

$string['col:resultnote']     = 'Poznámka';
$string['col:userid']         = 'ID';
$string['col:resultfinal']    = 'Výsledek řešení [%]';
$string['col:resultoutput']   = 'Výstup [%]';
$string['col:resultmemory']   = 'Paměť [%]';
$string['col:resulttime']     = 'Čas [%]';
$string['col:ordinal']        = '';
$string['col:taskid']         = 'Číslo úlohy';
$string['col:language']       = 'Jazyk';
$string['col:detail']         = 'Detail';
$string['col:runtime']        = 'Čas';
$string['col:runoutput']      = 'Výstup';
$string['col:runmemory']      = 'Paměť';
$string['col:code']           = 'Kód';
$string['col:plags']          = 'Plagiátorství';
$string['col:plagstimecheck'] = 'Datum kontroly plagiátorství';

$string['col:first']   = 'Uživatel A';
$string['col:second']  = 'Uživatel B';
$string['col:result']  = 'Výsledek';
$string['col:details'] = 'Poznámka';

$string['legend:memory']      = '%s - %s kB (%s×)';
$string['legend:time']        = '%s - %s ms (%s×)';
$string['legend:resultfinal'] = '%s - %s %% (%s×)';
$string['legend:memory_x']      = '%s kB (%s×)';
$string['legend:time_x']        = '%s ms (%s×)';
$string['legend:resultfinal_x'] = '%s %% (%s×)';