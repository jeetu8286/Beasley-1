<?php


require_once dirname(__FILE__) . "\57\x69\156\x63\154\165\x64\145\x73\x2f\154\x69\142\57\x6d\157\55\x6f\160\164\151\x6f\x6e\x73\55\145\156\x75\x6d\56\x70\x68\x70";
add_action("\141\144\x6d\151\x6e\x5f\x69\x6e\151\164", "\155\x6f\x5f\163\x61\x6d\x6c\x5f\165\x70\x64\x61\x74\x65");
class mo_saml_update_framework
{
    private $current_version;
    private $update_path;
    private $plugin_slug;
    private $slug;
    private $plugin_file;
    private $new_version_changelog;
    public function __construct($Pn, $Ls = "\x2f", $u0 = "\57")
    {
        $this->current_version = $Pn;
        $this->update_path = $Ls;
        $this->plugin_slug = $u0;
        list($bq, $vT) = explode("\57", $u0);
        $this->slug = $bq;
        $this->plugin_file = $vT;
        add_filter("\x70\162\145\137\x73\x65\164\137\163\151\x74\x65\x5f\x74\162\x61\x6e\x73\151\x65\156\x74\x5f\x75\160\x64\141\164\145\x5f\160\154\x75\x67\x69\x6e\x73", array(&$this, "\x6d\x6f\137\x73\x61\155\x6c\x5f\143\x68\145\143\x6b\137\x75\x70\144\141\x74\145"));
        add_filter("\x70\x6c\x75\x67\x69\156\163\137\141\160\x69", array(&$this, "\x6d\157\137\163\141\x6d\x6c\137\x63\150\x65\x63\153\137\151\x6e\x66\x6f"), 10, 3);
    }
    public function mo_saml_check_update($Bf)
    {
        if (!empty($Bf->checked)) {
            goto uV;
        }
        return $Bf;
        uV:
        $BJ = $this->getRemote();
        if (isset($BJ["\163\x74\x61\x74\165\163"]) and $BJ["\x73\164\x61\164\165\163"] == "\123\125\x43\x43\105\123\123") {
            goto Nl;
        }
        if (!(isset($BJ["\x73\x74\141\x74\x75\x73"]) and $BJ["\163\x74\141\164\165\x73"] == "\104\105\116\111\x45\x44")) {
            goto g2;
        }
        error_log("\x49\x6e\x20\x44\x65\156\151\145\144" . print_r($Bf, true));
        if (!version_compare($this->current_version, $BJ["\x6e\x65\167\126\145\x72\163\x69\x6f\x6e"], "\74")) {
            goto ut;
        }
        $hT = new stdClass();
        $hT->slug = $this->slug;
        $hT->new_version = $BJ["\x6e\145\x77\126\x65\x72\x73\151\157\x6e"];
        $hT->url = "\x68\x74\x74\x70\x73\x3a\57\x2f\x6d\x69\x6e\151\157\162\x61\x6e\147\145\x2e\143\157\155";
        $hT->plugin = $this->plugin_slug;
        $hT->tested = $BJ["\x63\x6d\163\x43\157\x6d\x70\141\164\x69\142\151\154\x69\164\x79\x56\145\162\x73\x69\x6f\156"];
        $hT->icons = array("\61\170" => $BJ["\x69\143\157\156"]);
        $hT->status_code = $BJ["\163\164\141\x74\165\x73"];
        $hT->license_information = $BJ["\x6c\x69\x63\145\156\163\x65\111\x6e\146\157\162\155\x61\164\151\x6f\156"];
        update_site_option("\x6d\157\137\163\141\x6d\x6c\137\154\x69\x63\x65\x6e\x73\x65\x5f\x65\170\x70\x69\x72\171\137\x64\x61\164\x65", $BJ["\154\x69\x63\x65\156\145\x45\170\160\x69\x72\171\x44\141\x74\x65"]);
        $Bf->response[$this->plugin_slug] = $hT;
        $tM = true;
        update_site_option("\155\x6f\137\163\141\x6d\x6c\x5f\x73\154\145", $tM);
        set_transient("\x75\160\x64\141\x74\145\x5f\160\x6c\165\147\151\x6e\163", $Bf);
        return $Bf;
        ut:
        g2:
        goto bM;
        Nl:
        $tM = false;
        update_site_option("\155\x6f\137\x73\141\x6d\154\x5f\x73\x6c\145", $tM);
        if (!version_compare($this->current_version, $BJ["\x6e\145\167\x56\x65\x72\x73\151\x6f\156"], "\x3c")) {
            goto HL;
        }
        ini_set("\x6d\x61\170\137\x65\170\145\143\165\x74\151\157\x6e\x5f\x74\151\155\x65", 600);
        ini_set("\155\x65\x6d\x6f\x72\171\137\154\151\155\151\x74", "\x31\60\x32\x34\x4d");
        $A2 = plugin_dir_path(__FILE__);
        $A2 = rtrim($A2, "\57");
        $A2 = rtrim($A2, "\x5c");
        $qO = $A2 . "\x2d\x70\x72\x65\x6d\151\165\155\x2d\142\x61\x63\153\x75\160\55" . $this->current_version . "\x2e\x7a\x69\x70";
        $this->mo_saml_create_backup_dir();
        $tT = $this->getAuthToken();
        $Vd = round(microtime(true) * 1000);
        $Vd = number_format($Vd, 0, '', '');
        $hT = new stdClass();
        $hT->slug = $this->slug;
        $hT->new_version = $BJ["\x6e\145\x77\126\x65\162\x73\151\x6f\156"];
        $hT->url = "\150\x74\x74\x70\163\x3a\57\x2f\x6d\151\x6e\151\157\x72\141\x6e\x67\x65\56\x63\x6f\155";
        $hT->plugin = $this->plugin_slug;
        $hT->package = mo_options_plugin_constants::HOSTNAME . "\57\155\x6f\141\x73\x2f\x70\x6c\165\x67\151\156\x2f\144\x6f\x77\156\154\x6f\141\144\x2d\x75\x70\x64\x61\x74\145\77\x70\154\165\x67\151\x6e\123\154\165\147\75" . $this->plugin_slug . "\46\x6c\151\143\145\x6e\x73\x65\x50\x6c\141\x6e\116\141\x6d\x65\75" . mo_options_plugin_constants::LICENSE_PLAN_NAME . "\46\x63\165\163\x74\157\155\145\162\111\144\x3d" . get_site_option("\x6d\157\137\163\141\x6d\x6c\x5f\x61\144\155\151\x6e\x5f\x63\165\x73\x74\157\x6d\x65\162\137\x6b\x65\171") . "\46\154\x69\x63\145\x6e\x73\145\x54\171\160\145\75" . mo_options_plugin_constants::LICENSE_TYPE . "\46\x61\x75\x74\x68\124\157\x6b\x65\156\75" . $tT . "\x26\157\164\160\124\x6f\153\145\x6e\75" . $Vd;
        $hT->tested = $BJ["\143\x6d\x73\x43\157\155\x70\141\164\151\142\x69\x6c\x69\164\x79\126\145\162\x73\x69\x6f\156"];
        $hT->icons = array("\61\170" => $BJ["\x69\143\x6f\x6e"]);
        $hT->new_version_changelog = $BJ["\143\x68\x61\x6e\x67\x65\x6c\157\147"];
        $hT->status_code = $BJ["\x73\x74\141\164\x75\163"];
        update_site_option("\x6d\157\x5f\x73\x61\155\x6c\137\154\x69\x63\x65\156\x73\145\x5f\x65\170\160\x69\162\171\x5f\144\141\x74\x65", $BJ["\154\151\x63\145\x6e\145\105\170\x70\x69\x72\171\x44\141\164\145"]);
        $Bf->response[$this->plugin_slug] = $hT;
        set_transient("\165\160\144\141\164\145\x5f\160\154\x75\x67\x69\156\163", $Bf);
        return $Bf;
        HL:
        bM:
        return $Bf;
    }
    public function mo_saml_check_info($hT, $Hg, $F_)
    {
        if (!(($Hg == "\x71\x75\x65\162\x79\x5f\160\154\165\x67\151\x6e\163" || $Hg == "\160\154\165\147\x69\156\137\x69\x6e\146\157\x72\155\x61\x74\151\157\x6e") && isset($F_->slug) && ($F_->slug === $this->slug || $F_->slug === $this->plugin_file))) {
            goto UC;
        }
        $C9 = $this->getRemote();
        remove_filter("\x70\154\165\147\x69\156\x73\137\141\x70\151", array($this, "\x6d\x6f\x5f\163\x61\x6d\x6c\x5f\x63\150\x65\143\153\x5f\151\x6e\x66\x6f"));
        $pu = plugins_api("\160\154\x75\147\151\156\137\151\156\x66\157\162\x6d\x61\x74\x69\157\156", array("\163\x6c\165\147" => $this->slug, "\x66\151\x65\154\144\x73" => array("\x61\143\164\151\166\145\x5f\151\x6e\163\x74\141\x6c\x6c\163" => true, "\156\x75\x6d\137\x72\x61\x74\x69\x6e\147\163" => true, "\x72\x61\164\x69\x6e\x67" => true, "\x72\141\164\151\x6e\147\x73" => true, "\162\145\166\x69\145\x77\x73" => true)));
        $ng = false;
        $o7 = false;
        $x6 = false;
        $BZ = false;
        $mD = '';
        $ot = '';
        if (is_wp_error($pu)) {
            goto qH;
        }
        $ng = $pu->active_installs;
        $o7 = $pu->rating;
        $x6 = $pu->ratings;
        $BZ = $pu->num_ratings;
        $mD = $pu->sections["\x64\145\x73\x63\162\151\160\x74\151\157\x6e"];
        $ot = $pu->sections["\x72\145\x76\x69\145\167\163"];
        qH:
        add_filter("\x70\x6c\x75\x67\x69\x6e\163\137\141\x70\151", array($this, "\155\157\137\163\141\155\154\x5f\x63\150\145\143\153\x5f\x69\x6e\146\157"), 10, 3);
        if ($C9["\163\164\141\x74\165\x73"] == "\x53\x55\x43\x43\105\123\123") {
            goto q4;
        }
        if (!($C9["\163\164\x61\164\x75\x73"] == "\x44\105\x4e\111\x45\104")) {
            goto g3;
        }
        if (!version_compare($this->current_version, $C9["\156\x65\x77\x56\145\162\x73\151\157\156"], "\x3c")) {
            goto RL;
        }
        $OJ = new stdClass();
        $OJ->slug = $this->slug;
        $OJ->plugin = $this->plugin_slug;
        $OJ->name = $C9["\160\x6c\165\x67\151\x6e\116\x61\155\145"];
        $OJ->version = $C9["\x6e\145\x77\x56\145\162\163\151\157\156"];
        $OJ->new_version = $C9["\156\145\x77\x56\145\162\x73\x69\157\x6e"];
        $OJ->tested = $C9["\x63\155\163\x43\157\155\160\x61\164\x69\x62\x69\x6c\151\x74\171\126\x65\x72\x73\151\157\x6e"];
        $OJ->requires = $C9["\x63\155\163\115\x69\156\126\x65\x72\163\x69\x6f\156"];
        $OJ->requires_php = $C9["\x70\150\160\115\151\156\126\x65\162\x73\x69\157\156"];
        $OJ->compatibility = array($C9["\143\155\163\x43\157\155\160\141\164\151\142\151\154\x69\164\x79\x56\x65\162\163\151\157\x6e"]);
        $OJ->url = $C9["\x63\x6d\x73\x50\154\165\147\151\156\x55\x72\154"];
        $OJ->author = $C9["\x70\154\x75\147\x69\156\101\x75\164\150\157\162"];
        $OJ->author_profile = $C9["\160\x6c\165\x67\151\x6e\x41\x75\164\150\157\162\x50\162\157\146\x69\154\145"];
        $OJ->last_updated = $C9["\x6c\x61\163\x74\x55\160\x64\x61\164\x65\144"];
        $OJ->banners = array("\154\x6f\x77" => $C9["\x62\x61\x6e\x6e\x65\x72"]);
        $OJ->icons = array("\61\x78" => $C9["\151\143\x6f\156"]);
        $OJ->sections = array("\x63\150\x61\156\147\x65\154\x6f\x67" => $C9["\x63\x68\x61\x6e\x67\x65\x6c\x6f\147"], "\x6c\x69\x63\x65\x6e\x73\145\x5f\151\156\146\157\x72\x6d\x61\x74\151\x6f\156" => _x($C9["\x6c\151\143\x65\x6e\x73\x65\x49\x6e\x66\x6f\162\155\x61\x74\x69\157\156"], "\120\x6c\165\x67\151\156\x20\x69\156\x73\164\x61\x6c\x6c\x65\x72\x20\163\x65\143\x74\151\x6f\x6e\x20\164\x69\164\154\145"), "\x64\x65\x73\143\162\151\160\164\151\x6f\156" => $mD, "\x52\145\166\151\x65\167\163" => $ot);
        $OJ->external = '';
        $OJ->homepage = $C9["\x68\x6f\x6d\145\x70\141\x67\x65"];
        $OJ->reviews = true;
        $OJ->active_installs = $ng;
        $OJ->rating = $o7;
        $OJ->ratings = $x6;
        $OJ->num_ratings = $BZ;
        update_site_option("\x6d\157\137\163\141\155\154\137\x6c\x69\x63\145\x6e\x73\145\137\x65\x78\160\151\x72\x79\x5f\x64\141\164\x65", $C9["\x6c\151\x63\145\x6e\x65\x45\x78\160\x69\162\x79\104\141\164\x65"]);
        return $OJ;
        RL:
        g3:
        goto FG;
        q4:
        $tM = false;
        update_site_option("\155\x6f\137\163\141\x6d\154\137\x73\154\145", $tM);
        if (!version_compare($this->current_version, $C9["\156\x65\x77\x56\x65\x72\163\151\x6f\156"], "\74\75")) {
            goto eE;
        }
        $OJ = new stdClass();
        $OJ->slug = $this->slug;
        $OJ->name = $C9["\x70\x6c\x75\x67\151\x6e\116\x61\155\x65"];
        $OJ->plugin = $this->plugin_slug;
        $OJ->version = $C9["\156\145\x77\126\x65\162\163\x69\157\x6e"];
        $OJ->new_version = $C9["\x6e\x65\x77\126\x65\162\x73\151\x6f\x6e"];
        $OJ->tested = $C9["\x63\155\163\x43\x6f\155\x70\x61\x74\151\142\x69\x6c\x69\164\171\126\x65\x72\163\151\x6f\x6e"];
        $OJ->requires = $C9["\x63\155\x73\x4d\151\x6e\126\x65\x72\x73\x69\x6f\156"];
        $OJ->requires_php = $C9["\160\x68\x70\115\x69\x6e\x56\145\x72\163\x69\157\156"];
        $OJ->compatibility = array($C9["\143\155\x73\103\x6f\155\160\141\x74\151\x62\x69\x6c\x69\x74\x79\x56\x65\162\x73\x69\x6f\x6e"]);
        $OJ->url = $C9["\x63\x6d\x73\120\x6c\x75\x67\x69\x6e\x55\x72\x6c"];
        $OJ->author = $C9["\160\x6c\x75\147\x69\156\x41\165\x74\x68\x6f\162"];
        $OJ->author_profile = $C9["\x70\154\165\x67\x69\156\101\x75\164\x68\x6f\162\120\x72\157\x66\151\x6c\145"];
        $OJ->last_updated = $C9["\154\x61\163\164\125\x70\144\x61\x74\145\x64"];
        $OJ->banners = array("\154\157\167" => $C9["\142\141\x6e\x6e\x65\162"]);
        $OJ->icons = array("\61\x78" => $C9["\x69\143\x6f\156"]);
        $OJ->sections = array("\x63\150\141\x6e\x67\145\x6c\157\x67" => $C9["\143\x68\x61\x6e\147\145\x6c\x6f\x67"], "\154\151\x63\145\156\x73\145\x5f\151\156\146\157\x72\155\x61\164\151\157\156" => _x($C9["\x6c\151\x63\x65\156\163\145\111\x6e\146\x6f\162\x6d\x61\164\151\157\x6e"], "\x50\x6c\x75\147\151\x6e\40\x69\x6e\x73\164\x61\154\x6c\145\x72\40\x73\145\x63\164\x69\x6f\156\40\x74\151\x74\154\145"), "\x64\x65\163\x63\162\151\160\164\x69\157\156" => $mD, "\x52\145\x76\151\145\167\163" => $ot);
        $tT = $this->getAuthToken();
        $Vd = round(microtime(true) * 1000);
        $Vd = number_format($Vd, 0, '', '');
        $OJ->download_link = mo_options_plugin_constants::HOSTNAME . "\57\x6d\x6f\141\x73\x2f\160\154\x75\147\x69\156\x2f\x64\157\x77\156\x6c\157\141\144\x2d\165\160\144\141\164\x65\77\x70\154\165\x67\x69\156\x53\x6c\165\147\75" . $this->plugin_slug . "\46\154\151\x63\x65\x6e\x73\x65\120\154\x61\156\116\x61\x6d\x65\x3d" . mo_options_plugin_constants::LICENSE_PLAN_NAME . "\x26\143\165\x73\x74\x6f\155\145\162\111\x64\x3d" . get_site_option("\155\157\x5f\163\x61\155\x6c\x5f\141\x64\x6d\x69\x6e\137\x63\165\x73\164\x6f\x6d\145\162\137\x6b\x65\171") . "\x26\x6c\151\143\x65\x6e\163\145\x54\x79\x70\x65\x3d" . mo_options_plugin_constants::LICENSE_TYPE . "\46\141\x75\164\x68\x54\157\153\x65\156\75" . $tT . "\x26\x6f\x74\160\x54\157\x6b\x65\156\x3d" . $Vd;
        $OJ->package = $OJ->download_link;
        $OJ->external = '';
        $OJ->homepage = $C9["\x68\x6f\155\145\160\x61\147\145"];
        $OJ->reviews = true;
        $OJ->active_installs = $ng;
        $OJ->rating = $o7;
        $OJ->ratings = $x6;
        $OJ->num_ratings = $BZ;
        update_site_option("\x6d\157\x5f\x73\x61\x6d\x6c\x5f\x6c\151\x63\145\156\163\145\x5f\145\x78\x70\151\x72\171\137\x64\141\x74\x65", $C9["\x6c\151\x63\145\x6e\145\x45\170\160\x69\x72\x79\x44\141\x74\145"]);
        return $OJ;
        eE:
        FG:
        UC:
        return $hT;
    }
    private function getRemote()
    {
        $Z8 = get_site_option("\155\x6f\137\163\141\x6d\x6c\x5f\141\x64\155\151\156\x5f\x63\165\x73\x74\x6f\x6d\145\162\x5f\x6b\x65\x79");
        $qM = get_site_option("\x6d\x6f\137\x73\x61\155\154\137\x61\144\155\151\156\x5f\141\x70\x69\137\153\x65\171");
        $Vd = round(microtime(true) * 1000);
        $W9 = $Z8 . number_format($Vd, 0, '', '') . $qM;
        $tT = hash("\x73\150\141\65\x31\62", $W9);
        $Vd = number_format($Vd, 0, '', '');
        $iH = array("\x70\x6c\165\x67\151\x6e\123\x6c\165\x67" => $this->plugin_slug, "\154\x69\143\x65\156\163\x65\120\154\141\156\x4e\141\155\145" => mo_options_plugin_constants::LICENSE_PLAN_NAME, "\143\165\163\x74\157\155\x65\x72\111\x64" => $Z8, "\x6c\x69\143\x65\156\x73\x65\x54\171\160\145" => mo_options_plugin_constants::LICENSE_TYPE);
        $WL = array("\150\x65\141\x64\x65\162\x73" => array("\x43\157\x6e\x74\145\156\x74\x2d\x54\171\160\145" => "\x61\x70\160\154\x69\143\x61\164\x69\x6f\156\57\x6a\163\157\x6e\73\40\x63\x68\x61\x72\163\x65\164\x3d\x75\164\x66\55\x38", "\x43\x75\x73\164\157\x6d\x65\x72\55\113\145\x79" => $Z8, "\x54\151\x6d\x65\163\x74\x61\x6d\160" => $Vd, "\x41\x75\x74\150\157\x72\151\172\141\x74\x69\157\156" => $tT), "\142\x6f\x64\x79" => json_encode($iH), "\155\x65\164\x68\157\144" => "\120\x4f\123\124", "\x64\141\x74\141\137\x66\x6f\x72\x6d\x61\x74" => "\142\157\144\x79", "\x73\163\x6c\x76\x65\x72\151\146\x79" => false);
        $iW = wp_remote_post($this->update_path, $WL);
        if (!(!is_wp_error($iW) || wp_remote_retrieve_response_code($iW) === 200)) {
            goto D2;
        }
        $j7 = json_decode($iW["\x62\x6f\144\x79"], true);
        return $j7;
        D2:
        return false;
    }
    private function getAuthToken()
    {
        $Z8 = get_site_option("\x6d\x6f\x5f\163\141\155\x6c\x5f\141\x64\x6d\151\156\137\143\x75\x73\164\157\x6d\x65\162\x5f\x6b\x65\x79");
        $qM = get_site_option("\155\157\x5f\163\x61\155\x6c\137\x61\x64\x6d\x69\156\x5f\141\x70\x69\137\x6b\145\171");
        $Vd = round(microtime(true) * 1000);
        $W9 = $Z8 . number_format($Vd, 0, '', '') . $qM;
        $tT = hash("\163\x68\x61\65\x31\x32", $W9);
        return $tT;
    }
    function zipData($P6, $LW)
    {
        if (!(extension_loaded("\172\151\160") && file_exists($P6) && count(glob($P6 . DIRECTORY_SEPARATOR . "\x2a")) !== 0)) {
            goto Pj;
        }
        $p1 = new ZipArchive();
        if (!$p1->open($LW, ZIPARCHIVE::CREATE)) {
            goto iw;
        }
        $P6 = realpath($P6);
        if (is_dir($P6) === true) {
            goto i_;
        }
        if (!is_file($P6)) {
            goto w7;
        }
        $p1->addFromString(basename($P6), file_get_contents($P6));
        w7:
        goto Nc;
        i_:
        $pU = new RecursiveDirectoryIterator($P6);
        $pU->setFlags(RecursiveDirectoryIterator::SKIP_DOTS);
        $YM = new RecursiveIteratorIterator($pU, RecursiveIteratorIterator::SELF_FIRST);
        foreach ($YM as $Cz) {
            $Cz = realpath($Cz);
            if (is_dir($Cz) === true) {
                goto RP;
            }
            if (!(is_file($Cz) === true)) {
                goto XL;
            }
            $p1->addFromString(str_replace($P6 . DIRECTORY_SEPARATOR, '', $Cz), file_get_contents($Cz));
            XL:
            goto PY;
            RP:
            $p1->addEmptyDir(str_replace($P6 . DIRECTORY_SEPARATOR, '', $Cz . DIRECTORY_SEPARATOR));
            PY:
            tj:
        }
        Ek:
        Nc:
        iw:
        return $p1->close();
        Pj:
        return false;
    }
    function mo_saml_plugin_update_message($O0, $iW)
    {
        if ($O0["\x73\x74\141\x74\x75\163\137\x63\x6f\x64\x65"] == "\x53\x55\x43\x43\x45\123\123") {
            goto xc;
        }
        if (!($O0["\163\x74\141\164\x75\163\x5f\x63\157\144\x65"] == "\104\x45\x4e\x49\105\x44")) {
            goto JF;
        }
        echo sprintf(__($O0["\x6c\x69\143\x65\x6e\163\145\x5f\x69\156\x66\157\162\155\141\164\x69\157\156"]));
        JF:
        goto K6;
        xc:
        $A2 = plugin_dir_path(dirname(__FILE__));
        $A2 = rtrim($A2, "\57");
        $A2 = rtrim($A2, "\x5c");
        $qO = "\155\x69\x6e\x69\x6f\x72\x61\x6e\147\145\55\x73\x61\155\154\x2d\x32\x30\x2d\163\151\156\147\x6c\145\55\x73\151\x67\x6e\55\157\x6e\55\160\162\145\155\x69\165\x6d\55\142\141\x63\153\165\x70\x2d" . $this->current_version . "\56\172\x69\x70";
        $N0 = explode("\74\57\165\x6c\76", $O0["\156\x65\x77\x5f\x76\145\x72\x73\x69\x6f\x6e\137\143\150\141\156\x67\145\x6c\157\x67"]);
        $Qm = $N0[0];
        $WV = $Qm . "\74\x2f\165\x6c\x3e";
        echo "\x3c\144\151\166\x3e\x3c\x62\x3e" . __("\x3c\x62\x72\40\x2f\x3e\x41\x6e\40\141\165\x74\x6f\x6d\x61\x74\x69\143\40\142\x61\x63\153\165\x70\40\x6f\146\40\143\165\x72\162\x65\x6e\164\x20\x76\x65\162\x73\x69\x6f\156\x20" . $this->current_version . "\40\x68\141\x73\x20\142\145\145\156\40\x63\162\145\x61\164\x65\144\40\141\x74\x20\x74\150\145\x20\x6c\157\143\141\164\151\157\156\x20" . $A2 . "\x20\x77\x69\x74\x68\40\164\150\145\40\156\x61\x6d\145\x20\74\x73\160\141\156\40\163\x74\171\154\x65\75\42\143\157\154\157\x72\x3a\x23\x30\x30\67\63\x61\x61\73\42\x3e" . $qO . "\74\57\163\160\x61\x6e\76\x2e\x20\x49\156\40\143\141\x73\145\x2c\40\x73\x6f\155\x65\164\150\x69\x6e\147\40\142\x72\x65\x61\153\163\40\144\165\x72\x69\x6e\x67\x20\x74\150\145\x20\x75\160\144\x61\x74\x65\54\40\171\157\x75\x20\143\141\x6e\x20\x72\145\x76\145\162\164\x20\164\157\40\171\157\165\x72\x20\143\165\x72\x72\x65\156\164\x20\x76\x65\162\x73\x69\157\x6e\x20\x62\171\40\x72\145\x70\154\x61\x63\x69\156\x67\40\x74\x68\x65\40\x62\x61\x63\x6b\x75\x70\x20\165\163\151\x6e\x67\x20\106\x54\120\40\141\x63\143\x65\163\163\x2e", "\155\x69\x6e\x69\x6f\162\141\x6e\147\145\55\x73\141\155\154\55\62\60\55\163\151\156\147\154\x65\x2d\163\x69\147\x6e\x2d\157\156") . "\74\x2f\x62\x3e\74\x2f\144\x69\x76\76\x3c\144\x69\x76\x20\x73\164\171\154\x65\75\42\x63\157\x6c\157\162\x3a\x20\43\x66\x30\60\x3b\x22\76" . __("\74\x62\162\40\x2f\76\124\x61\x6b\145\40\141\x20\155\x69\x6e\x75\x74\145\x20\x74\157\x20\143\x68\x65\143\x6b\x20\164\x68\145\x20\x63\x68\141\156\x67\145\x6c\x6f\x67\40\x6f\146\40\154\141\x74\x65\x73\164\40\x76\x65\x72\163\x69\x6f\156\40\157\146\40\164\150\145\40\x70\154\165\147\151\x6e\56\x20\110\145\x72\x65\47\x73\x20\167\x68\x79\40\171\157\x75\40\156\145\145\144\40\x74\157\40\x75\160\144\141\x74\x65\72", "\155\x69\x6e\151\157\162\141\x6e\147\145\x2d\x73\141\155\x6c\55\62\60\x2d\163\x69\x6e\147\154\x65\x2d\x73\151\x67\x6e\x2d\157\x6e") . "\x3c\x2f\x64\x69\166\76";
        echo "\x3c\x64\x69\x76\x20\x73\x74\171\x6c\145\75\x22\x66\157\x6e\164\55\167\x65\x69\147\x68\164\72\40\156\157\162\155\141\x6c\x3b\42\x3e" . $WV . "\74\x2f\144\151\x76\76\x3c\142\76\x4e\x6f\164\x65\x3a\x3c\57\x62\76\40\x50\154\x65\x61\163\x65\x20\x63\154\151\143\x6b\40\157\x6e\x20\74\x62\x3e\126\151\145\167\x20\x56\x65\x72\x73\151\x6f\156\x20\x64\x65\164\141\x69\154\163\74\x2f\x62\x3e\x20\154\x69\x6e\x6b\x20\x74\157\x20\147\145\x74\40\143\157\155\160\154\145\164\x65\x20\x63\150\x61\x6e\147\145\154\157\147\40\141\x6e\144\x20\x6c\x69\143\145\156\163\145\x20\151\x6e\146\x6f\162\155\x61\164\x69\x6f\x6e\56\x20\103\154\x69\x63\x6b\40\x6f\x6e\x20\74\x62\76\125\160\144\141\164\145\40\x4e\157\x77\x3c\57\x62\x3e\x20\154\151\x6e\x6b\40\164\x6f\40\165\160\x64\141\x74\x65\40\164\x68\x65\x20\x70\154\x75\x67\151\156\40\x74\157\40\154\141\164\145\163\x74\40\166\x65\x72\x73\151\x6f\x6e\56";
        K6:
    }
    public function mo_saml_license_key_notice()
    {
        if (!array_key_exists("\155\157\163\x61\155\154\x2d\144\151\x73\155\x69\163\x73", $_GET)) {
            goto qR;
        }
        return;
        qR:
        if (!(get_site_option("\155\x6f\x5f\163\141\x6d\x6c\137\163\154\145") && new DateTime() > get_site_option("\x6d\157\x2d\x73\141\155\x6c\55\160\154\165\x67\151\156\55\x74\x69\155\145\x72"))) {
            goto Si;
        }
        $Oy = esc_url(add_query_arg(array("\155\157\x73\x61\155\154\55\144\x69\163\155\x69\x73\x73" => wp_create_nonce("\x73\x61\155\x6c\55\144\151\x73\155\x69\163\163"))));
        echo "\74\163\x63\x72\x69\160\x74\x3e\xd\12\x9\11\11\x9\146\x75\156\x63\164\x69\157\156\x20\155\x6f\123\x41\x4d\114\x50\141\x79\155\x65\156\x74\123\164\x65\x70\x73\50\x29\x20\173\15\12\11\11\11\x9\11\x76\x61\162\40\x61\164\164\162\40\x3d\x20\x64\157\143\x75\x6d\x65\156\x74\56\x67\145\164\x45\x6c\x65\x6d\x65\156\x74\102\171\x49\x64\50\42\x6d\x6f\x73\x61\x6d\x6c\160\141\171\155\x65\156\x74\x73\x74\x65\x70\163\42\51\56\x73\x74\171\x6c\145\x2e\x64\x69\163\160\x6c\x61\x79\x3b\xd\12\11\x9\x9\11\x9\x69\x66\x28\x61\164\x74\162\40\75\75\x20\x22\x6e\x6f\156\x65\x22\51\x7b\xd\12\x9\x9\11\x9\x9\11\144\157\143\165\155\145\x6e\164\56\x67\x65\x74\105\x6c\145\x6d\145\x6e\x74\x42\171\x49\x64\x28\42\x6d\157\163\141\x6d\x6c\160\141\x79\155\145\156\x74\163\x74\x65\x70\163\x22\51\x2e\x73\x74\x79\x6c\145\56\x64\151\163\160\154\141\171\x20\x3d\40\x22\x62\x6c\x6f\x63\153\42\73\xd\12\11\x9\11\x9\x9\x7d\x65\x6c\163\145\x7b\15\12\11\x9\11\x9\x9\11\x64\x6f\x63\x75\x6d\x65\x6e\x74\56\147\x65\164\x45\154\145\x6d\x65\156\x74\102\171\x49\144\x28\42\155\x6f\163\x61\155\x6c\160\141\171\x6d\145\156\x74\x73\x74\x65\160\163\x22\51\56\x73\164\x79\154\x65\56\144\151\163\x70\x6c\x61\171\40\75\x20\42\x6e\x6f\156\145\42\73\xd\12\11\x9\11\x9\x9\175\xd\xa\x9\11\11\11\175\xd\xa\x9\x9\11\74\57\163\x63\x72\x69\x70\164\76";
        echo "\x3c\x64\151\x76\x20\151\144\75\x22\x6d\x65\x73\x73\x61\x67\145\42\x20\163\x74\x79\x6c\145\75\x22\160\x6f\x73\x69\164\x69\x6f\x6e\72\x72\x65\154\141\x74\151\166\145\x22\40\143\x6c\x61\163\x73\75\42\x6e\157\x74\151\143\x65\x20\x6e\x6f\164\x69\x63\145\x20\156\x6f\164\151\x63\x65\x2d\x77\x61\x72\x6e\151\156\147\x22\x3e\74\x62\x72\40\x2f\76\74\x73\x70\141\156\x20\143\154\141\163\163\75\42\141\154\x69\147\x6e\x6c\145\146\164\x22\x20\163\164\x79\x6c\145\x3d\x22\x63\x6f\x6c\157\x72\x3a\43\141\60\60\x3b\146\x6f\x6e\164\55\x66\x61\x6d\x69\x6c\171\72\40\55\167\x65\142\153\151\164\55\x70\x69\x63\164\x6f\x67\x72\x61\x70\x68\73\146\x6f\x6e\164\x2d\163\x69\172\x65\x3a\40\x32\65\160\170\73\x22\x3e\x49\x4d\x50\117\122\x54\x41\116\x54\x21\x3c\57\x73\x70\141\x6e\x3e\74\x62\162\x20\57\x3e\74\x69\x6d\147\x20\163\x72\x63\75\x22" . plugin_dir_url(__FILE__) . "\x69\x6d\141\147\145\x73\x2f\x6d\151\156\x69\x6f\x72\x61\156\x67\145\x2d\x6c\x6f\x67\x6f\x2e\160\156\147" . "\42\x20\x63\x6c\x61\x73\x73\75\42\x61\154\151\x67\156\x6c\x65\x66\x74\42\x20\150\x65\x69\147\x68\x74\x3d\x22\70\x37\x22\40\167\151\x64\164\150\75\42\66\x36\42\40\141\x6c\x74\x3d\x22\155\x69\x6e\151\x4f\x72\141\x6e\147\145\x20\x6c\157\147\157\x22\40\x73\x74\171\x6c\145\75\x22\x6d\141\x72\147\x69\156\x3a\61\60\x70\170\x20\61\x30\160\x78\x20\x31\x30\x70\170\40\x30\73\x20\x68\x65\x69\x67\150\x74\x3a\x31\62\70\x70\x78\73\x20\x77\151\x64\x74\150\x3a\40\61\x32\x38\x70\x78\x3b\x22\76\74\x68\63\x3e\155\x69\156\x69\117\x72\141\156\x67\145\x20\x53\x41\x4d\114\40\x32\x2e\60\40\x53\x69\x6e\x67\x6c\x65\40\x53\151\x67\x6e\x2d\x4f\156\40\123\x75\x70\x70\x6f\162\164\40\x26\x20\x4d\141\151\x6e\x74\145\156\x61\156\x63\145\40\x4c\151\143\145\x6e\163\145\40\x45\170\x70\x69\x72\145\x64\x3c\x2f\150\x33\x3e\74\160\x3e\131\157\165\162\40\x6d\151\156\x69\x4f\x72\x61\156\x67\x65\40\123\x41\x4d\114\x20\x32\x2e\60\x20\x53\151\x6e\x67\x6c\145\40\123\151\x67\156\55\117\x6e\x20\154\151\x63\145\156\x73\145\40\x69\x73\x20\x65\x78\160\151\x72\145\x64\56\x20\124\150\151\163\40\155\145\x61\156\x73\40\171\157\165\342\200\231\162\x65\40\155\151\163\x73\151\x6e\147\x20\157\165\x74\x20\x6f\x6e\x20\x6c\141\x74\x65\163\164\x20\x73\145\143\165\x72\151\x74\x79\40\x70\x61\x74\143\150\145\163\x2c\40\x63\157\x6d\160\x61\164\x69\142\x69\x6c\151\x74\171\40\x77\x69\x74\x68\x20\x74\x68\145\40\154\x61\164\145\x73\164\x20\x50\x48\x50\x20\166\145\162\163\151\157\x6e\x73\40\141\156\x64\x20\127\157\162\x64\160\x72\x65\163\x73\x2e\x20\115\157\x73\x74\x20\151\155\x70\157\x72\164\x61\x6e\x74\x6c\171\x20\171\157\x75\342\200\231\154\154\40\x62\x65\40\155\151\163\163\x69\x6e\x67\x20\x6f\165\164\x20\157\x6e\x20\x6f\165\x72\40\x61\167\145\163\157\155\x65\40\163\165\x70\160\x6f\162\x74\41\40\74\x2f\x70\76\15\12\11\11\74\160\76\x3c\141\40\150\x72\145\x66\x3d\x22" . mo_options_plugin_constants::HOSTNAME . "\57\155\x6f\x61\163\x2f\x6c\157\147\x69\x6e\x3f\162\145\144\151\x72\x65\143\x74\x55\x72\x6c\x3d" . mo_options_plugin_constants::HOSTNAME . "\x2f\x6d\157\x61\x73\57\x61\x64\155\x69\156\57\143\165\x73\x74\x6f\155\145\x72\x2f\x6c\151\x63\145\156\x73\x65\x72\x65\x6e\x65\x77\x61\154\163\x3f\162\145\156\x65\x77\141\x6c\162\145\x71\165\145\x73\164\75" . mo_options_plugin_constants::LICENSE_TYPE . "\x22\40\x63\154\141\x73\x73\75\42\x62\165\164\164\157\156\x20\x62\165\x74\164\x6f\156\x2d\160\162\151\155\141\162\x79\42\40\164\x61\x72\147\x65\x74\x3d\x22\137\142\x6c\141\x6e\x6b\42\76\x52\x65\x6e\x65\x77\40\171\x6f\x75\x72\40\x73\165\160\x70\x6f\x72\x74\40\x6c\x69\143\x65\x6e\x73\145\x3c\x2f\x61\76\46\156\142\x73\x70\73\46\156\142\163\160\73\x3c\142\x3e\74\141\40\150\162\145\146\75\42\43\42\x20\157\156\143\154\x69\143\153\x3d\42\x6d\x6f\123\x41\115\x4c\120\x61\x79\x6d\145\x6e\x74\x53\x74\145\160\163\50\51\42\x3e\x43\154\x69\x63\x6b\40\x68\145\x72\x65\74\57\x61\x3e\x20\x74\157\40\x6b\156\x6f\167\40\150\157\167\40\x74\x6f\40\162\145\x6e\x65\167\x3f\74\57\142\x3e\74\x64\x69\166\x20\x69\144\x3d\42\x6d\x6f\163\x61\155\154\x70\x61\x79\x6d\145\156\x74\x73\x74\145\160\163\42\x20\x20\163\x74\x79\x6c\145\75\42\144\x69\163\160\x6c\x61\x79\72\40\156\x6f\x6e\145\x3b\x22\76\74\x62\x72\x20\57\x3e\74\x75\x6c\40\163\x74\171\x6c\x65\x3d\42\x6c\151\x73\x74\x2d\x73\164\x79\x6c\145\72\40\x64\151\163\143\73\155\141\x72\147\x69\156\55\154\145\146\164\x3a\40\x31\65\x70\x78\x3b\x22\76\xd\xa\74\154\151\x3e\103\x6c\x69\143\x6b\x20\157\156\40\x61\142\157\x76\145\40\x62\x75\x74\x74\157\156\40\164\157\x20\154\x6f\x67\151\x6e\40\151\x6e\x74\x6f\40\x6d\x69\156\151\x4f\x72\x61\x6e\147\x65\x2e\x3c\57\x6c\151\x3e\15\12\x3c\154\x69\x3e\x59\x6f\x75\40\167\x69\154\x6c\40\142\x65\40\x72\x65\144\x69\x72\x65\143\x74\145\x64\x20\x74\157\x20\160\154\165\147\x69\156\x20\162\145\x6e\145\x77\141\154\40\160\x61\147\145\x20\x61\x66\x74\x65\162\40\x6c\157\147\151\156\x2e\74\x2f\x6c\151\x3e\15\12\74\154\151\x3e\x49\x66\40\164\150\x65\x20\x70\x6c\165\147\x69\156\40\154\151\x63\145\x6e\163\145\40\160\x6c\x61\156\x20\151\x73\x20\156\157\164\x20\163\x65\x6c\145\143\x74\145\x64\40\x74\150\x65\156\x20\143\x68\x6f\157\x73\x65\x20\164\150\x65\40\x72\x69\x67\150\164\x20\x6f\156\x65\x20\x66\162\157\155\x20\164\x68\145\40\144\162\157\x70\144\157\x77\x6e\x2c\x20\x6f\164\x68\145\162\167\151\x73\145\40\x63\x6f\x6e\164\x61\143\164\x20\x3c\142\76\x3c\x61\40\x68\x72\x65\x66\x3d\x22\x6d\x61\x69\154\x74\157\72\x69\156\146\157\x40\170\x65\x63\165\162\x69\x66\171\56\x63\x6f\x6d\x2e\x63\x6f\x6d\x22\x3e\151\156\x66\x6f\100\x78\x65\x63\165\x72\x69\x66\171\56\x63\157\155\56\143\157\155\74\57\141\x3e\x3c\57\x62\x3e\x20\164\x6f\x20\153\x6e\157\x77\40\141\x62\157\x75\164\x20\x79\x6f\165\162\40\154\x69\143\x65\x6e\163\145\40\160\x6c\141\156\x2e\74\x2f\154\x69\76\15\12\74\154\151\76\131\x6f\165\x20\167\151\154\154\x20\163\145\x65\40\x74\x68\145\40\160\x6c\x75\147\x69\156\x20\162\145\156\145\x77\x61\154\40\141\x6d\x6f\x75\x6e\x74\x2e\74\57\154\151\x3e\15\xa\x3c\x6c\151\76\x46\x69\x6c\154\x20\x75\x70\40\171\157\x75\x72\40\x43\162\x65\144\151\164\x20\103\x61\x72\144\x20\151\x6e\x66\x6f\162\155\141\x74\151\x6f\156\40\164\157\40\x6d\x61\x6b\x65\x20\164\150\145\40\x70\141\171\155\x65\156\x74\x2e\x3c\57\x6c\151\76\15\xa\74\154\151\x3e\117\x6e\x63\x65\x20\164\150\145\40\x70\141\171\x6d\145\x6e\x74\x20\151\163\x20\144\x6f\x6e\x65\x2c\40\x63\154\151\x63\x6b\x20\157\156\40\74\142\x3e\x43\x68\145\x63\x6b\40\101\x67\141\151\156\x3c\x2f\x62\76\40\x62\165\x74\x74\x6f\x6e\x20\x66\x72\157\x6d\x20\164\x68\145\40\x46\157\162\x63\x65\40\125\160\x64\x61\164\x65\40\x61\x72\145\141\40\157\x66\40\171\157\165\162\x20\x57\157\162\x64\x50\x72\145\163\163\x20\141\x64\x6d\x69\156\x20\x64\141\163\x68\142\x6f\141\x72\x64\40\x6f\162\40\x77\x61\x69\x74\40\x66\x6f\162\x20\x61\40\x64\141\171\x20\164\157\40\147\145\164\x20\x74\150\x65\x20\141\165\164\x6f\155\x61\x74\151\x63\x20\x75\x70\144\x61\164\145\56\x3c\x2f\154\151\x3e\15\xa\x3c\154\151\x3e\103\x6c\151\x63\153\40\x6f\x6e\x20\x3c\142\76\x55\160\144\141\164\x65\40\x4e\157\167\74\x2f\x62\76\40\154\151\x6e\153\40\x74\157\40\x69\x6e\163\164\141\154\x6c\x20\164\x68\145\x20\154\x61\164\145\x73\164\x20\166\145\162\163\x69\x6f\156\40\x6f\146\x20\x74\150\x65\x20\x70\154\165\147\151\156\x20\x66\162\x6f\x6d\40\160\x6c\x75\x67\x69\156\x20\x6d\x61\x6e\x61\147\x65\162\x20\141\162\x65\x61\40\x6f\x66\x20\171\x6f\165\x72\x20\x61\x64\155\151\156\40\x64\141\x73\150\142\x6f\141\162\144\56\74\57\x6c\151\x3e\xd\12\x3c\57\x75\x6c\x3e\x49\x6e\x20\x63\x61\x73\145\x2c\40\171\157\x75\40\x61\162\x65\x20\146\141\143\x69\x6e\147\40\141\x6e\171\x20\x64\151\146\x66\151\x63\x75\154\x74\x79\40\151\156\x20\x69\x6e\x73\x74\141\x6c\x6c\x69\x6e\147\x20\x74\x68\145\40\165\160\x64\141\x74\145\x2c\x20\160\x6c\x65\x61\163\x65\40\143\x6f\156\x74\141\x63\x74\40\74\x62\76\x3c\141\x20\x68\162\x65\146\x3d\x22\x6d\141\x69\154\164\x6f\x3a\151\x6e\146\157\100\170\x65\x63\165\162\151\146\x79\56\143\157\155\x2e\x63\157\x6d\42\x3e\151\156\146\x6f\100\170\x65\x63\x75\x72\151\146\171\56\x63\157\155\x2e\x63\x6f\155\x3c\x2f\141\76\x3c\x2f\142\x3e\x2e\xd\xa\117\165\162\40\123\165\x70\160\157\162\x74\40\x45\170\145\x63\165\164\151\x76\x65\40\167\151\x6c\154\40\x61\x73\x73\x69\163\164\x20\171\x6f\x75\40\x69\x6e\40\x69\156\x73\x74\x61\x6c\x6c\x69\x6e\x67\40\164\x68\x65\x20\165\x70\144\141\x74\x65\163\x2e\x3c\142\162\40\x2f\76\74\x69\x3e\x46\x6f\162\40\x6d\x6f\162\145\40\x69\156\x66\x6f\x72\155\141\164\151\x6f\156\54\40\160\154\145\141\163\145\40\x63\x6f\x6e\x74\141\143\x74\x20\x3c\142\76\74\141\x20\150\162\145\x66\75\42\x6d\141\151\154\164\x6f\x3a\151\156\146\157\100\170\145\x63\165\x72\x69\146\171\56\x63\x6f\155\56\143\157\x6d\42\x3e\151\156\x66\157\x40\170\145\x63\x75\x72\151\x66\171\56\x63\x6f\155\56\x63\157\x6d\74\57\x61\76\74\x2f\142\76\x2e\74\x2f\151\76\74\x2f\x64\x69\166\76\x3c\141\x20\x68\x72\x65\x66\75\x22" . $Oy . "\42\40\143\x6c\x61\163\x73\75\x22\141\x6c\151\147\x6e\x72\x69\147\x68\x74\x20\x62\165\164\164\x6f\156\40\x62\165\x74\x74\x6f\x6e\55\x6c\x69\156\x6b\x22\x3e\104\x69\x73\x6d\x69\163\163\x3c\x2f\141\76\74\57\160\x3e\15\12\x9\11\x3c\144\x69\x76\x20\143\154\141\163\163\75\x22\143\154\145\141\162\42\x3e\74\x2f\144\151\166\x3e\74\57\x64\151\x76\x3e";
        Si:
    }
    public function mo_saml_dismiss_notice()
    {
        if (!empty($_GET["\155\x6f\163\x61\155\x6c\x2d\144\x69\x73\155\x69\163\163"])) {
            goto NM;
        }
        return;
        NM:
        if (wp_verify_nonce($_GET["\155\157\x73\141\155\154\x2d\144\x69\163\155\x69\x73\163"], "\163\x61\x6d\154\55\144\x69\163\x6d\x69\163\163")) {
            goto T6;
        }
        return;
        T6:
        if (!(isset($_GET["\155\157\163\x61\155\x6c\x2d\x64\151\x73\155\151\163\163"]) && wp_verify_nonce($_GET["\x6d\157\x73\x61\x6d\154\55\x64\151\x73\155\x69\x73\x73"], "\x73\141\x6d\154\55\x64\x69\163\155\151\x73\x73"))) {
            goto AK;
        }
        $Aj = new DateTime();
        $Aj->modify("\x2b\x31\x20\x64\x61\171");
        update_site_option("\x6d\x6f\55\163\x61\155\154\x2d\x70\154\165\147\x69\156\55\164\x69\155\x65\x72", $Aj);
        AK:
    }
    function mo_saml_create_backup_dir()
    {
        $A2 = plugin_dir_path(__FILE__);
        $A2 = rtrim($A2, "\x2f");
        $A2 = rtrim($A2, "\x5c");
        $O0 = get_plugin_data(__FILE__);
        $Bo = $O0["\124\x65\170\x74\x44\157\x6d\x61\x69\x6e"];
        $fE = wp_upload_dir();
        $Qu = $fE["\x62\141\163\x65\x64\x69\162"];
        $fE = rtrim($Qu, "\x2f");
        $sk = $fE . DIRECTORY_SEPARATOR . "\142\141\143\153\x75\x70" . DIRECTORY_SEPARATOR . $Bo . "\x2d\x6d\165\x6c\164\x69\163\151\164\x65\55\142\x61\x63\153\165\160\x2d" . $this->current_version;
        if (file_exists($sk)) {
            goto KS;
        }
        mkdir($sk, 511, true);
        KS:
        $P6 = $A2;
        $LW = $sk;
        $this->mo_saml_copy_files_to_backup_dir($P6, $LW);
    }
    function mo_saml_copy_files_to_backup_dir($A2, $sk)
    {
        if (!is_dir($A2)) {
            goto Tf;
        }
        $a7 = scandir($A2);
        Tf:
        if (!empty($a7)) {
            goto Vh;
        }
        return;
        Vh:
        foreach ($a7 as $Zg) {
            if (!($Zg == "\56" || $Zg == "\56\x2e")) {
                goto H7;
            }
            goto qj;
            H7:
            $Ee = $A2 . DIRECTORY_SEPARATOR . $Zg;
            $p5 = $sk . DIRECTORY_SEPARATOR . $Zg;
            if (is_dir($Ee)) {
                goto po;
            }
            copy($Ee, $p5);
            goto EX;
            po:
            if (file_exists($p5)) {
                goto on;
            }
            mkdir($p5, 511, true);
            on:
            $this->mo_saml_copy_files_to_backup_dir($Ee, $p5);
            EX:
            qj:
        }
        rH:
    }
}
function mo_saml_update()
{
    $aZ = mo_options_plugin_constants::HOSTNAME;
    $Bn = mo_options_plugin_constants::Version;
    $ch = $aZ . "\x2f\155\x6f\141\x73\x2f\x61\160\x69\57\160\154\165\147\x69\156\57\x6d\145\x74\x61\x64\x61\x74\141";
    $u0 = plugin_basename(dirname(__FILE__) . "\x2f\x6c\x6f\147\151\x6e\56\x70\x68\x70");
    $IK = new mo_saml_update_framework($Bn, $ch, $u0);
    add_action("\151\156\137\160\154\165\x67\151\156\137\165\160\144\x61\164\x65\x5f\x6d\x65\163\x73\x61\x67\x65\55{$u0}", array($IK, "\x6d\x6f\x5f\163\x61\155\154\x5f\x70\154\165\x67\x69\156\x5f\165\x70\x64\x61\164\145\137\x6d\145\163\163\141\147\145"), 10, 2);
    add_action("\141\144\x6d\151\156\137\150\145\x61\x64", array($IK, "\155\157\137\163\x61\155\x6c\137\154\x69\x63\145\156\x73\145\137\x6b\145\x79\137\x6e\x6f\x74\x69\x63\x65"));
    add_action("\156\145\x74\x77\x6f\x72\x6b\137\141\144\155\151\x6e\137\156\x6f\164\x69\x63\145\x73", array($IK, "\155\157\137\163\x61\x6d\154\137\x64\151\x73\155\x69\163\x73\137\x6e\157\164\x69\143\x65"), 50);
    if (!get_site_option("\155\x6f\x5f\163\x61\155\154\137\163\154\x65")) {
        goto lZ;
    }
    update_site_option("\x6d\x6f\x5f\x73\x61\x6d\154\137\163\154\145\x5f\155\145\163\163\141\x67\x65", "\131\157\x75\x72\x20\123\101\115\x4c\x20\160\x6c\165\x67\x69\156\40\154\151\x63\145\156\163\145\40\x68\141\x73\x65\x20\x62\x65\145\x6e\40\x65\x78\x70\x69\162\x65\x64\56\x20\x59\157\165\x20\x61\x72\145\40\x6d\151\x73\x73\151\156\147\x20\157\x75\164\x20\157\156\40\x75\160\x64\x61\x74\145\163\x20\141\156\x64\40\x73\165\x70\160\157\x72\164\41\x20\120\154\145\x61\163\145\x20\74\141\x20\x68\162\x65\146\75\x22" . mo_options_plugin_constants::HOSTNAME . "\x2f\x6d\x6f\141\x73\x2f\154\157\147\x69\156\77\162\x65\x64\x69\162\x65\x63\x74\x55\162\x6c\x3d" . mo_options_plugin_constants::HOSTNAME . "\57\155\x6f\141\x73\x2f\x61\144\x6d\151\156\57\143\x75\163\164\x6f\155\145\162\57\x6c\x69\x63\145\x6e\x73\x65\162\x65\x6e\x65\167\141\154\163\77\x72\x65\156\145\167\141\154\162\x65\161\x75\x65\x73\x74\x3d" . mo_options_plugin_constants::LICENSE_TYPE . "\x20\x22\40\164\x61\x72\x67\x65\x74\x3d\x22\x5f\142\154\x61\156\153\x22\x3e\x3c\142\x3e\103\154\x69\x63\153\40\x48\145\162\145\x3c\57\x62\76\74\x2f\x61\76\40\x74\x6f\40\162\x65\156\x65\x77\x20\164\x68\x65\40\x53\165\160\x70\x6f\162\x74\x20\141\156\x64\x20\115\x61\x69\156\x74\x65\x6e\x61\143\145\40\x70\x6c\x61\156\56");
    lZ:
}
