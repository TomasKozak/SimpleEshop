-- phpMyAdmin SQL Dump
-- version 4.0.4.2
-- http://www.phpmyadmin.net
--

--
-- Štruktúra tabuľky pre tabuľku `elektro_objednavky`
--

DROP TABLE IF EXISTS `elektro_objednavky`;
CREATE TABLE IF NOT EXISTS `elektro_objednavky` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_pouz` smallint(6) NOT NULL,
  `adresa` text COLLATE utf8_slovak_ci NOT NULL,
  `tovar` smallint(6) NOT NULL,
  `pocet_ks` smallint(6) NOT NULL,
  `doprava` varchar(10) COLLATE utf8_slovak_ci NOT NULL,
  `datum_odberu` date NOT NULL,
  `cena_spolu` float NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_slovak_ci AUTO_INCREMENT=4 ;

--
-- Sťahujem dáta pre tabuľku `elektro_objednavky`
--

INSERT INTO `elektro_objednavky` (`id`, `id_pouz`, `adresa`, `tovar`, `pocet_ks`, `doprava`, `datum_odberu`, `cena_spolu`) VALUES
(1, 5, 'FMFI, Mlynská dolina, Bratislava', 14, 2, 'kurier', '2016-04-21', 878),
(2, 6, 'Súmračná, Košice', 3, 1, 'vlastna', '2016-04-25', 33),
(3, 4, 'Školská, Žilina', 11, 4, 'kurier', '2016-04-26', 212);

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `elektro_pouzivatelia`
--

DROP TABLE IF EXISTS `elektro_pouzivatelia`;
CREATE TABLE IF NOT EXISTS `elektro_pouzivatelia` (
  `id_pouz` smallint(6) NOT NULL AUTO_INCREMENT,
  `prihlasmeno` varchar(20) COLLATE utf8_slovak_ci NOT NULL,
  `heslo` varchar(50) COLLATE utf8_slovak_ci NOT NULL DEFAULT '',
  `meno` varchar(20) COLLATE utf8_slovak_ci NOT NULL DEFAULT '',
  `priezvisko` varchar(30) COLLATE utf8_slovak_ci NOT NULL DEFAULT '',
  `admin` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_pouz`),
  UNIQUE KEY `username` (`prihlasmeno`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_slovak_ci AUTO_INCREMENT=7 ;

--
-- Sťahujem dáta pre tabuľku `elektro_pouzivatelia`
--

INSERT INTO `elektro_pouzivatelia` (`id_pouz`, `prihlasmeno`, `heslo`, `meno`, `priezvisko`, `admin`) VALUES
(1, 'admin', '21232f297a57a5a743894a0e4a801fc3', 'Administrátor', 'systému', 1),
(2, 'uwa', '78f0f32c08873cfdba57f17c855943c0', 'predmet', 'UWA', 0),
(3, 'roman', 'b179a9ec0777eae19382c14319872e1b', 'Roman', 'Hrušecký', 1),
(4, 'marek', 'e061c9aea5026301e7b3ff09e9aca2cf', 'Marek', 'Nagy', 0),
(5, 'jozko', '256f035bd7cf72238fad007fb9199c66', 'Jožko', 'Púčik', 0),
(6, 'mrkva', 'bfd7d9c62540ed72de0f32932077bef7', 'Janko', 'Mrkvička', 0);

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `elektro_tovar`
--

DROP TABLE IF EXISTS `elektro_tovar`;
CREATE TABLE IF NOT EXISTS `elektro_tovar` (
  `kod` smallint(6) NOT NULL AUTO_INCREMENT,
  `nazov` varchar(100) COLLATE utf8_slovak_ci NOT NULL,
  `popis` text COLLATE utf8_slovak_ci NOT NULL,
  `cena` float NOT NULL,
  `na_sklade` smallint(6) NOT NULL,
  PRIMARY KEY (`kod`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_slovak_ci AUTO_INCREMENT=19 ;

--
-- Sťahujem dáta pre tabuľku `elektro_tovar`
--

INSERT INTO `elektro_tovar` (`kod`, `nazov`, `popis`, `cena`, `na_sklade`) VALUES
(1, 'Beko WMD', 'Práčka predom plnená, rýchlosť odstreďovania 1000 ot./min, náplň bielizne 5kg, energetická trieda A, účinnosť prania A, odložený štart 3-6-9 hodín, detský zámok, voliteľné otáčky, 20 programov, funkcia rýchleho prania, funkcia jednoduchého žehlenia, spotreba vody 49 l, farba biela, rozmery VxŠxH 85x60x54', 266, 20),
(2, 'Haier HW', 'Práčka predom plnená, otáčky 1000-400 ot./min, energetická trieda A, trieda účinnosti prania A, trieda efektivity odstreďovania C, počet programov 11, náplň 1-5 kg, zvláštne funkcie: elektronické ovládacie prvky, displej, časovač odloženého prania 0,5–24 h, velké dvierka (30 cm), uhol otvárania 180°, rýchle pranie 29 min., program ľahké žehlenie, 3úrovňový load systém hladiny vody, Jemné pranie, program Sport, rozmery (v/š/h) 85 x 59,5 x 52', 150, 10),
(3, 'INDESIT WIDL', 'Práčka so sušiškou, 1200 ot./min., 1-5 kg, 4 kg pre sušenie, automatické riadenie spotreby vody, 14 programov prania, 2 sušiace programy, nastavenie času sušenia, Time4You, Šport, Jednoduché žehlenie, odložený štart, hand wash, daily wash, hodvab, Overflow, trieda B', 33, 30),
(6, 'CANDY CSW', 'Práčka so sušičkou, Smart Alisé, 5 kg prania / 2,5 kg sušenia, 1000 ot/min, C/B, 2 stlpce LED, odložený štart, elektronický programátor, Aquaplus, ľahké žehlenie, športové oblečenie, 6 úrovní sušenia, 85x60x44 cm ÚZKA', 315, 100),
(7, 'ETA 2458', 'Podlahový vysávač, dĺžka prívodného kábla 5 m, akčný rádius 8 m, vstupný filter - 2-vrstvový mikrofilter, štrbinová hubica, vankúšová hubica, elektronická regulácia výkonu, signalizácia plnosti vrecka na prach, parkovacia poloha na hubicu, príkon 1800 W, vstupný hepa filter, samonavíjací kábel', 83, 13),
(8, 'Amica GEP', 'Kombinovaný sporák, plynová varná doska - 2x stredný, 1x malý, 1x velký horák, elektrický programátor, multifunkčná rúra - 8 funkcií (samostatné svetlo, spodné ohrevné teleso, vrchné ohrevné teleso, klasické pečenie, gril, supergril, gril + ventilátor, teplvzdušná funkcia, funkcia pizza, časovač, samočistenie parou), smaltovaný príklop, elektrické zapaľovanie v každom gombíku, gril 2000W, dvojité sklo dvierok, spodná zásuvka na plechy, objem rúry 54l, príslušenstvo: 1x rošt, rozmery (v š h) 85 x 50 60 cm', 330, 0),
(9, 'Sencor SVC', 'odlahový vysávač, antikorová teleskopická trubica, elektronická regulácia sacieho výkonu, 6 násobný filtračný systém, indikátor naplnenia sáčku na prach, samonavíjací prívodný kábel, parkovacia poloha, kapacita sáčku na prach - 2,5l, dĺžka sieťového káblu 5,2 m, hlučnosť 80 dB (A), max. príkon 1800W, príslušenstvo v základnom vybavení: podlahová kefa, hubica na ťažko dostupne miesta, nástavec na čalúnenie', 40, 0),
(10, 'Hoover Freespace', 'Podlahový vysávač, maximálny príkon 1800 W, sací výkon 310 W, umývateľný HEPA predmotorový filter, umývateľný výstupný filter Purefilt, objem sáčku 2,3 l, rádius 8 m, elektronický ukazovateľ plnosti sáčku, posuvná elektronická regulácia výkonu, príslušenstvo: štrbinová hubica, kefka na prach', 76, 46),
(11, 'Sencor SMW', 'Mikrovlná rúra, mechanické ovládanie, objem 17L, výkon 700W, časovač 30 min, 6 úrovni výkonu, rozmrazovanie, akustická signalizácia, prevedenie strieborné', 53, 80),
(12, 'Haier EA', 'Mikrovlnka, typ digitálny, farba biela, objem 20 l, kombinované varenie (mikrovlnka + gril), počet programov 8, max. výkon - mikrovlnný 800 W, max. výkon - gril 1000 W, zvláštne funkcie: detská poistka, kombinované varenie (mikrovlnka + gril), automatická volba programov, rýchly štart, rozmrazovanie, akustická signalizácia, hodiny', 90, 23),
(13, 'Daewoo KOR', 'Mikrovlnka, vnútorný objem 20 l, výkon 700 W, vnútorný priestor akrylát, 5 úrovní výkonu, systém ohrevu C.R.S., funkcia rozmrazovania, otočný tanier z ohňovzdorného skla, mechanické ovládanie, akustická signalizácia, otváranie dvierok ťahom', 43, 9),
(14, 'BOSCH SGS45N18', 'umývačka riadu, energetická trieda A, účinnosť umývania A, účinnosť sušenia A, kapacita 12 súprav riadu, 3+1 auto program, rýchly program VarioSpeed, intenzívny 70°C, rýchly 45°C, auto 55°-65°C, eco 50°C, auto 3 v 1, spotreba el. energie 1,05 kWh, spotreba vody 13 l, odložený štart, displej, hlučnosť 49 dB, v x š x h: 85 x 60 x 60 cm, nerezové dvere, lakované boky', 439, 8),
(15, 'ELECTROLUX ESF 43020', 'umývačka riadu, energetická trieda A, kapacita 9 súprav riadu, spotreba vody 13 l, spotreba el. energie 0,8 kW, Fuzzy Logic, Aqua Control hadica ', 329, 12),
(16, 'AEG F 60660', 'umývačka riadu GREEN range, kapacita 12 súprav riadu, energetická trieda A, účinnosť umývania A, účinnosť sušenia A, spotreba vody 12 l, spotreba el. energie 1,05 kW, 7 programov, Auto 45-70°, nočný cyklus, rýchly 60, Multitab, displej, odlož.štart, Aqua control, vodný senzor, Fuzzy Logic, hlučnosť 44 dB, v x š x h: 85 x 59,6 x 62,5 cm, farba: biela ', 429, 2),
(17, 'BEKO FE 566E', 'elektrický sporák, 4 liatinové platne, dvojité sklo dvierok, 1 plech + 1 rošt, pripájacie napätie:230-400V, v x š x h: 85 x 50 x 60 cm, farba: biela', 199, 33),
(18, 'INDESIT K 3C51 W', 'sklokeramický sporák, energetická trieda : B, 4x sklokeram.varné zóny, ukazovateľ zvyškov.tepla, dvojité sklo dvierok rúry, multifunkčná rúra, 5 programov, objem rúry 54l, vrchný, spodný ohrev, ventilátor a kombinácie, gril, minútnik, vnútorné osvetlenie rúry, úložný priestor-zásuvka, plech, 1gril. rošt, rozmery vxšxh:85x50x60cm, farba:biela ', 299, 20);
