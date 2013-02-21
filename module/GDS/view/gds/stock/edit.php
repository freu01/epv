<?php
// module/Album/view/album/album/add.phtml:

$title = $entrepot->nom;
$title .= ' : Modifier un stock';
$this->headTitle($title);
?>
<h1><?php echo $this->escapeHtml($title); ?></h1>
<?php
$form = $this->form;
$form->setAttribute('action', $this->url('stock', array('action' => 'add', 'id' => $entrepot->id)));
$form->prepare();

echo $this->form()->openTag($form);
echo $this->formHidden($form->get('id'));
echo $this->formHidden($form->get('idEntrepot'));
echo $this->formRow($form->get('idProduit'));
echo $this->formRow($form->get('quantite'));
echo $this->formSubmit($form->get('submit'));
echo $this->form()->closeTag();