<script src="https://cdn.tiny.cloud/1/<?php echo $tiny_mce_id; ?>/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>

<script>
tinymce.init({
  selector: 'textarea',
  width: "100%",
  height: 500,
  remove_linebreaks : true,
  convert_urls: false,
  plugins: [
    'advlist autolink link lists charmap print preview hr image anchor pagebreak spellchecker',
    'searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking',
    'table emoticons template paste help'
  ],
  toolbar: ' bold italic underline | fontsizeselect | forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media',
  menu: {
    favs: {title: 'My Favorites', items: 'code visualaid | searchreplace | spellchecker | link'}
  },
  menubar: 'file edit view insert format tools table help',
  content_css: 'css/content.css'
});

$(document).on('focusin', function(e) {
    if ($(e.target).closest(".tox-dialog").length) {
        e.stopImmediatePropagation();
    }
});
</script>
