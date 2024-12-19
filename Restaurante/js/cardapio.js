function aparecer(classeId) {
    const itens = document.getElementById(`itens-${classeId}`);
    if (itens) {
        itens.classList.toggle('visu');
    }
}
