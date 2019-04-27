function onClickClustering() {
  let cutTree = document.getElementById('cutTree')
  cutTree.value= (cutTree.value==='')?0:''
  cutTree.style.display=(cutTree.value === '0')?'none':''
  

}
function onClick(elem, index) {
  let pages = document.getElementsByClassName('pages')[index]
  let proteinstoshowNode = document.getElementsByClassName('proteinsToShow')[index]
  let inputNbProt = document.getElementsByClassName('inputNbProt')[index]
  let text = document.getElementsByClassName('text')[index]


  let proteins = Array.prototype.slice.call(proteinstoshowNode.children)
  let nbProtPerPage = proteins.length
  let nbPages = Math.round(proteins.length / nbProtPerPage)
  let mode = proteinstoshowNode.style.display

  proteinstoshowNode.style.display = (mode == '') ? 'none' : ''
  elem.innerHTML = (mode == '' ? 'Show more' : 'Hidden')
  elem.className = (mode == '' ? 'btn btn-primary' : 'btn btn-outline-info')
  pages.style.display = (mode == '') ? 'none' : ''


  inputNbProt.setAttribute("value", nbProtPerPage)
  inputNbProt.setAttribute("onChange", "updateSelector(" + index + ",this.value," + proteins.length + ")")
  inputNbProt.min = 1
  inputNbProt.max = proteins.length
  text.innerText = inputNbProt.value + " prot par page - Page 1 sur " + nbPages

  updateSelector(index, inputNbProt.value, proteins.length)


}

function updateSelector(index, nbProtPerPage, protLength) {
  let selector = document.getElementsByClassName('selector')[index]
  let pages = document.getElementsByClassName('pages')[index]
  let text = document.getElementsByClassName('text')[index]
  let nbPages = Math.ceil(protLength / nbProtPerPage)
  //MàJ du Select, on supprime les pages qui sont plus d'actualités
  while (selector.children[nbPages]) {
    selector.removeChild(selector.children[nbPages])
  }
  //On rajoute des pages au selecteur si nécessaire
  let beginning = selector.lastChild ? Number(selector.lastChild.textContent) : 0
  if (beginning < nbPages) {
    for (let i = beginning; i < nbPages; i++) {
      let op = document.createElement('OPTION')
      op.innerHTML = (i + 1)
      selector.appendChild(op)

    }
  }
  //On met à jour onChange
  selector.setAttribute("onChange", "changePage(" + index + ", (this.selectedIndex+1)," + nbProtPerPage + ")")
  //on se remet à la page 1 à chaque fois que le nombre de pages change
  changePage(index, 1, nbProtPerPage)
  text.innerText = nbProtPerPage + " prot par page - Page 1 sur " + nbPages

}

function changePage(index, pageNumber, nbProtPerPage) {
  let proteinstoshowNode = document.getElementsByClassName('proteinsToShow')[index]
  let pages = document.getElementsByClassName('pages')[index]
  let text = document.getElementsByClassName('text')[index]

  let proteins = Array.prototype.slice.call(proteinstoshowNode.children)

  let debutPage = pageNumber * nbProtPerPage - nbProtPerPage
  debutPage = (debutPage < 0) ? 0 : debutPage
  let finPage = pageNumber * nbProtPerPage

  if (nbProtPerPage > 0) {
    for (let prot of proteins) {
      prot.style.display = 'none'
    }
    for (let prot of proteins.slice(debutPage, finPage)) {
      prot.style.display = ''
    }
  }
  text.innerText = nbProtPerPage + " prot par page - Page " + pageNumber + " sur " + Math.ceil(proteins.length / nbProtPerPage)

}