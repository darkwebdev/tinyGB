<?
    include_once('core/model.inc.php');


    class Entry extends Model {
        protected $text;
        protected $author;

        public function __construct($data=[]) {
            parent::__construct();

            $this->text = new TextField('Content');

            $this->author = new ForeignKey();
            $this->author->model = 'User';
            $this->author->editable = false;

            $this->name->name = 'Title';

            $this->apply_data($data);
        }

        static public function get_active() {
            return self::get_all(['is_active' => true]);
        }

    }

?>