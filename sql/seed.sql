-- Données préalables à l'utilisation de Mancy

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `personnomancy`
--

--
-- Déchargement des données de la table `cite_etat`
--

INSERT INTO `cite_etat` (`id`, `nom`, `description`, `active`, `supprime`) VALUES
(1, 'Libera, la Cité libre', 'La Cité Libre s’étend sur un vaste territoire qui joint Libera (la capitale, anciennement Koro) et ce qu’il reste du Sud Unifié. Les citoyens de la Cité Libre se nomment les Libériens. Ces derniers constituent un peuple anarchique se regroupant autour de divers clans nomades. Ces clans se déplacent en gigantesques caravanes d’une oasis à l&#39;autre au rythme des saisons, faisant de Libera la cité-État couvrant le plus de territoire (mais pas la plus peuplée). Ici prédominent des idéaux de liberté, d’accueil et de découverte, le voyage et l’exploration coulant à même les veines des Libériens et Libériennes.', 1, 0),
(2, 'Numadril, la Cité Suspendue', 'La Cité Suspendue est aussi connue sous le nom de Numadril, sa capitale. Elle se situe au nord-est des Terres du Centre et s’étend sur les vastes forêts des anciens territoires de Satila et de Valion. Les citoyens habitant ce territoire s’organisent par tribus et préconisent une gestion de la société basée sur la gérontocratie. Les personnes les plus âgées de ce peuple sont considérées comme des sages et leurs décisions, mûrement réfléchies, sont toujours respectées. Dans cette cité, la nature est reine et son respect est primordial; cela se reflète dans les coutumes, les croyances et les philosophies des habitants. Le cycle de la vie y est sacré, tout comme la balance qui existe entre chacun des éléments : la terre, le feu, l’eau et l’air. Des protecteurs de la nature sont chargés de faire respecter cette balance, ces cycles ainsi que tous les êtres vivants qui y vieillissent.', 1, 0),
(3, 'Santa Purgare, la Cité Sainte', 'La Cité Sainte se trouve au centre ouest du continent à l’endroit où se trouvait la Santa Purgare, qui est devenue avec le temps une ville et ensuite la capitale de la région. La Cité Sainte est reconnue pour ses habitants très pieux, dont le sens de la justice et la foi en Élyon sont au centre de leurs valeurs. Ils sont prompts à défendre la veuve et l’orphelin, à venir en aide aux plus démunis et à chasser ce qu’ils considèrent comme surnaturel et mauvais. Leur foi inébranlable est parfois vue comme du zèle, ce qui crée des frictions avec les habitants des cités-États ne mettant pas au cœur de leur identité la religion.', 1, 0),
(4, 'Terra Fortuna, la Cité des Opportunités', 'La Cité des Opportunités se trouve complètement à l’ouest du continent et a comme capitale Terra Fortuna, située au sein de ce qui était à l’époque Rivadel. Cette région est principalement basée sur le commerce et la culture et se targue d&#39;être une terre d&#39;opportunité pour tous. La grande richesse de la région permet aux habitants de développer mode, art et musique. On y retrouve régulièrement des joutes de gladiateurs, des opéras et des foires d&#39;artisanat auxquelles tous sont conviés. La nourriture et le vin y étant produit ont une réputation à travers tout le continent, tout comme ses mœurs hédonistes et progressistes. Terra Fortuna est gouvernée par une République démocratique, un système novateur qu’ils ne cessent de comparer aux monarchies et autres systèmes considérés selon eux dépassés.', 1, 0),
(5, 'Valbirin, la Cité Mangefer', 'La Cité Mangefer se situe au nord-ouest du continent. Elle s’étale des villes d’Osphélia jusqu’à Valbirin, sa capitale. Une partie de ce territoire est souterrain. La communauté Mangefer a pour valeurs fondatrices le progrès et l’avancement scientifique. Les habitants de la région comptent des ingénieurs, marchands, médecins et forgerons, de même que leurs familles respectives. La politique de la cité est basée sur la méritocratie, signifiant que selon les besoins de l&#39;époque, la personne la plus utile était élue. En temps de guerre, un militaire fut élu, mais en temps de paix, il fut décidé qu’un technocrate devait diriger, de sorte à faire progresser la communauté scientifique. Les chefs de la cité Mangefer ont tous prouvé leur valeur par le biais de leur intelligence et raisonnement logique. Une structure organisée et réfléchie est valorisée.', 1, 0);

--
-- Déchargement des données de la table `croyance`
--

INSERT INTO `croyance` (`id`, `nom`, `description`, `active`, `supprime`) VALUES
(1, 'Culte des Esprits', 'Cette croyance a pour élément central le lien de communication entre notre monde et l’énergie vitale qui l’anime. Au centre de cette pratique, on retrouve des concepts de dévotions et d’offrandes afin d’obtenir la protection des esprits.', 1, 0),
(2, 'Église d&#39;Élyon', 'Les dogmes d’Élyon prônent des idéaux de bonté et de lumière. D’une part, les grands textes sacrés font état de la nécessité de pardonner, d’aider son prochain et de faire preuve de miséricorde. De l’autre, des passages soulignent la nécessité d’avoir une force de frappe pour détecter et châtier le mal : la Sainte Inquisition d’Élyon.', 1, 0),
(3, 'Scientisme', 'Cette croyance, qui relève davantage d’une philosophie que d’une religion, estime que les problèmes de l’existence devraient être remédié non pas par des dévotions à des dieux et esprits instables, mais plutôt par le biais d’un essor massif de la science, des connaissances et de l’innovation.', 1, 0);

--
-- Déchargement des données de la table `race`
--

INSERT INTO `race` (`id`, `nom`, `description`, `active`, `supprime`) VALUES
(1, 'Elfe', 'Les elfes, jadis connus comme l&#39;une des races vivant le plus longtemps dans les royaumes, ont connu un changement dramatique il y a environ 200 ans. Leur longévité s&#39;est réduite de manière significative, et aujourd&#39;hui, tous les elfes anciens, porteurs de sagesse et de traditions, sont morts de vieillesse. Cette transformation a profondément impacté la société elfique ; les jeunes elfes, autrefois rieurs et enjoués, sont désormais confrontés plus rapidement aux responsabilités et aux défis de l&#39;âge adulte. Ils développent une maturité et une sagesse à un rythme accéléré, adaptant leur mode de vie et leur culture à cette nouvelle réalité. Les elfes, toujours étroitement liés à la nature, continuent de former leurs civilisations en harmonie avec leur environnement, bien que certains choisissent de s&#39;établir dans des villes humaines, cherchant à comprendre et à s&#39;adapter à ce monde en constante évolution.', 1, 0),
(2, 'Humain', 'Peuple très hétéroclite et varié, les humains comportent toutes sortes d’individus. On les retrouve dans toutes les strates sociales et toutes les régions. Ils sont caractérisés par une faculté d’adaptation qui ne peut être égalée que par les orcs. Pour cela, la race des humains s’est étendue sur tout le continent et même par-delà les mers pour en faire la race la plus populeuse connue.', 1, 0),
(3, 'Lycan', 'Les lycans d&#39;aujourd&#39;hui sont des créatures uniques, touchées par la nature, émergeant spontanément dans des familles de diverses races. Cette nouvelle génération de lycans, considérée comme un signe de la faveur des dieux ou de la nature elle-même, est bien vue dans la plupart des cultures. Les anciens lycans, des êtres mi-homme mi-animal dont les ancêtres étaient lycanthropes, se sont éteints il y a longtemps, incapables de procréer après le désastre de la toile magique. Les lycans actuels sont intégrés harmonieusement au sein des communautés. Ils ne vivent plus en clans isolés, mais plutôt comme membres à part entière de la société, apportant leur force unique et leur connexion avec la nature. Leur naissance est souvent célébrée comme un cadeau précieux, et ils sont respectés pour leur lien spécial avec le monde naturel, sans la crainte et la méfiance réservé à leurs prédécesseurs lycanthropes.', 1, 0),
(4, 'Nain', 'Les nains sont de petites personnes, mesurant entre 3 et 5 pieds. Ils sont larges, robustes et portent une barbe longue entretenue avec fierté. Ils ont une structure sociale très définie et vivent généralement dans les montagnes ou les mines. Les nains évitent généralement les éclats impulsifs de violence : lorsqu’ils combattent, c’est surtout pour défendre leur territoire, leur honneur ou leurs amis. Ils sont perçus comme durs en affaire, mais, dans l’intimité, deviennent joviaux et blagueurs.', 1, 0),
(5, 'Orc', 'Les orcs sont une race connue pour leur nature féroce et leur aptitude au combat, ancrée profondément dans les royaumes de la nature sauvage. Ces êtres robustes et endurants sont réputés pour leur approche directe et pratique des défis, résolvant les problèmes avec une efficacité qui force le respect. Malgré leur réputation de guerriers implacables, les orcs entretiennent un lien étroit et respectueux avec la nature, tirant de leur environnement aussi bien leur force que leur inspiration. Leur société est structurée autour de la survie et de l&#39;adaptabilité, valorisant les compétences pratiques et l&#39;ingéniosité. Bien que certains orcs choisissent de s&#39;aventurer au-delà de leurs territoires naturels, la plupart préfèrent rester au cœur de leurs terres ancestrales, où ils peuvent vivre en harmonie avec le monde sauvage qui les a façonnés.', 1, 0),
(6, 'Petit-gens', 'Les petit-gens sont enjoués, amicaux et bienheureux. Malgré leur petite taille, leur curiosité (certains diront insouciance) les pousse parfois dans des situations étranges et dangereuses. Ils sont généralement grégaires et aiment la compagnie des autres. Bons vivants, ils aiment également la bonne nourriture, les fêtes et le repos. Ce sont ultimement des gens simples, parfois poussés à l’aventure par leur grande curiosité.', 1, 0);

--
-- Déchargement des données de la table `voie`
--

INSERT INTO `voie` (`id`, `nom`, `description`, `active`, `supprime`) VALUES
(1, 'Voie arcanique', 'La voie arcanique est réservée aux compétences de magie du même type, permettant aux joueurs d&#39;accéder à des pouvoirs mystiques pour lancer des sorts, relever les morts, ou manipuler les éléments. Elle est parfaite pour ceux qui cherchent à explorer les mystères de l&#39;univers.', 1, 0),
(2, 'Voie artisanale', 'La voie artisanale se concentre sur la conception, permettant aux joueurs de créer et de réparer une variété d&#39;objets. De la forge d&#39;armes à la confection de desserts, cette voie est essentielle pour soutenir l&#39;effort de guerre et la vie quotidienne.', 1, 0),
(3, 'Voie divine', 'La voie divine englobe les compétences spirituelles et de guérison, inspirées du rôle des prêtres. Elle offre des capacités de soutien telles que la guérison, la protection divine, et les bénédictions, cruciales pour la survie du groupe.', 1, 0),
(4, 'Voie générale', 'La voie générale comprend des compétences génériques accessibles à tous les joueurs, indépendamment de leur spécialisation. Ces compétences offrent une base solide et polyvalente, allant de la collecte à la négociation, essentielles pour tout aventurier.', 1, 0),
(5, 'Voie martiale', 'La voie martiale est dédiée aux compétences de combat, offrant aux joueurs la possibilité de maîtriser des techniques offensives et défensives. Des techniques de combat aux habiletés régénératives, cette voie forge les guerriers et les protecteurs de la nouvelle terre.', 1, 0);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
