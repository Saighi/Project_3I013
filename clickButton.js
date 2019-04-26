 function onClick(elem,index) {
						var proteinstoshowNode = elem.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode.getElementsByClassName('proteinsToShow')[index]
            var mode = proteinstoshowNode.style.display
            proteinstoshowNode.style.display = (mode == '') ? 'none' : ''
            elem.innerHTML = (mode == '' ? 'Show more' : 'Hidden')
            elem.className = (mode == '' ? 'btn btn-primary' : 'btn btn-outline-info')
        }