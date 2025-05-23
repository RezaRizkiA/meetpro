// Inisialisasi Quill untuk setiap elemen yang ditemukan
document.querySelectorAll('.editor_quill').forEach(editor => {
  new Quill(editor, {
    theme: 'snow',
  });
});