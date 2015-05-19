<?php
/**
 * Created by PhpStorm.
 * User: Alexandre
 * Date: 18/05/2015
 * Time: 19:37
 */

namespace App\Services;


use MongoCode;
use MongoClient;

class MapReduce
{


    private $db;

    /**
     * Simple connexion
     */
    public function __construct()
    {
        $mongo = new MongoClient();
        $this->db = $mongo->selectDB(env('mongo_database'));
    }

    /**
     * Aggregation of hashtags
     * @return array
     */
    public function agg_hashtags()
    {
        $collection = $this->db->selectCollection('hashtag_collection');

        $op = [
            ['$group' => ['_id' => '$hashtag.text', 'number' => ['$sum' => 1]]],
            ['$sort' => ['number' => -1]],
            ['$limit' => 20]
        ];

        return $collection->aggregate($op);
    }


    /**
     * map reduce for keeping keywords
     * @return array
     */
    public function mapreduce_keywords()
    {
        $map = new MongoCode('
            function () {
                var stopwords = ["a", "about", "above", "above", "across", "after",
                "afterwards", "again", "against", "all", "almost", "alone", "along",
                "already", "also","although","always","am","among", "amongst",
                "amoungst", "amount",  "an", "and", "another", "any","anyhow","anyone",
                "anything","anyway", "anywhere", "are", "around", "as",  "at", "back",
                "be","became", "because","become","becomes", "becoming", "been",
                "before", "beforehand", "behind", "being", "below", "beside",
                "besides", "between", "beyond", "bill", "both", "bottom","but",
                "by", "call", "can", "cannot", "cant", "co", "con", "could",
                "couldnt", "cry", "de", "describe", "detail",  "do", "does", "done",  "down",
                "due", "during", "each", "eg", "eight", "either", "eleven","else",
                "elsewhere", "empty", "enough", "etc", "even", "ever", "every",
                "everyone", "everything", "everywhere", "except", "few", "fifteen",
                "fify", "fill", "find", "fire", "first", "five", "for", "former",
                "formerly", "forty", "found", "four", "from", "front", "full",
                "further", "get", "give", "go", "had", "has", "hasnt", "have",
                "he", "hence", "her", "here", "hereafter", "hereby", "herein",
                "hereupon", "hers", "herself", "him", "himself", "his", "how",
                "however", "hundred", "ie", "if", "in", "inc", "indeed",
                "interest", "into", "is",  "it", "its", "itself", "keep",
                "last", "latter", "latterly", "least", "less", "ltd", "made",
                "many", "may", "me", "meanwhile", "might", "mill", "mine",
                "more", "moreover", "most", "mostly", "move", "much", "must", "my",
                "myself", "name", "namely", "neither", "never", "nevertheless", "next",
                "nine", "no", "nobody", "none", "noone", "nor", "not", "nothing", "now",
                "nowhere", "of", "off", "often", "on", "once", "one", "only", "onto",
                "or", "other", "others", "otherwise", "our", "ours", "ourselves", "out",
                "over", "own","part", "per", "perhaps", "please", "put", "rather", "re",
                "same", "see", "seem", "seemed", "seeming", "seems", "serious", "several",
                "she", "should", "show", "side", "since", "sincere", "six", "sixty", "so",
                "some", "somehow", "someone", "something", "sometime", "sometimes", "somewhere",
                "still", "such", "system", "take", "ten", "than", "that", "the", "their", "them",
                "themselves", "then", "thence", "there", "thereafter", "thereby", "therefore",
                "therein", "thereupon", "these", "they", "thickv", "thin", "third", "this",
                "those", "though", "three", "through", "throughout", "thru", "thus", "to",
                "together", "too", "top", "toward", "towards", "twelve", "twenty", "two",
                "un", "under", "until", "up", "upon", "us", "very", "via", "was", "way", "we",
                "well", "were", "what", "whatever", "when", "whence", "whenever", "where",
                "whereafter", "whereas", "whereby", "wherein", "whereupon", "wherever",
                "whether", "which", "while", "whither", "who", "whoever", "whole", "whom",
                "whose", "why", "will", "with", "within", "without", "would", "yet", "you",
                "your", "yours", "yourself", "yourselves", "the","didnt", "doesnt", "dont",
                "isnt", "wasnt", "youre", "hes", "ive", "theyll", "whos", "wheres", "whens",
                "whys", "hows", "whats", "were", "shes", "im", "thats", "just","did","This",
                "this","come","did","amp;",";","&amp;",

                "alors","au","aucuns","aussi","autre","avant","avec","avoir","bon","car","ce","cela",
                "ces","ceux","chaque","ci","comme","comment","dans","des","du","dedans","dehors","depuis",
                "devrait","doit","donc","dos","début","elle","elles","en","encore","essai","est","et","eu",
                "fait","faites","fois","font","hors","ici","il","ils","je","juste","la","le","les","leur",
                "là","ma","maintenant","mais","mes","mine","moins","mon","mot","même","ni","nommés","notre",
                "nous","ou","où","par","parce","pas","peut","peu","plupart","pour","pourquoi","quand","que",
                "quel","quelle","quelles","quels","qui","sa","sans","ses","seulement","si","sien","son","sont",
                "sous","soyez","sujet","sur","ta","tandis","tellement","tels","tes","ton","tous","tout","trop",
                "très","tu","voient","vont","votre","vous","vu","ça","étaient","état","étions","été","être"

                ];

                var patt = new RegExp("[a-zA-Z]{3,}");

                var words = this.text.split(" ");
                var keywords = [];
                for (var i = 0; i < words.length; i++) {
                        var word = words[i].toLowerCase().replace(/[^a-zA-Z]/, "");
                        if (stopwords.indexOf(word) === -1 && keywords.indexOf(word) === -1) {
                                keywords.push(word);
                        }
                }

                for (var i = 0; i < keywords.length; i++) {
                    if(patt.test(keywords[i])){
                        emit(keywords[i], { count : 1 });
                    }
                }
            }
        ');

        $reduce = new MongoCode('
            function (key, values) {
                var total = 0;
                for (var i = 0; i < values.length; i++) {
                    total += values[i].count;
                }
                return { count : total };
            }
        ');

        $collection = $this->db->selectCollection('tweet_collection');

        $collection->wtimeout = -1;

        return $collection->db->command(array(
            "mapreduce" => "tweet_collection",
            "map" => $map,
            "reduce" => $reduce,
            "out" => array("merge" => "map_reduce_twitter_words")),array("socketTimeoutMS" => -1));
    }
}