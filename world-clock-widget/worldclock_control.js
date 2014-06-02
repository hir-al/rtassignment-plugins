var worldclockIdEdit = -1;
var worldclockCityEdit = '';
var worldclockTzEdit = -1;
var worldclockDstEdit = false;

function worldclock_getElementsByClass(className) {
	var classes = new Array();
	var allPageTags=document.getElementsByTagName("*");
	for(i=0, j=0; i<allPageTags.length; i++) {
		if (allPageTags[i].className==className) {
			classes[j++] = allPageTags[i];
		}
	}
	return classes;
}

function worldclock_setDisplayNone(arrayElement, value) {
        for(var i=0; i<arrayElement.length; ++i) {
                arrayElement[i].style.display = 'none';
        }
}

function worldclock_setDisplayBlock(arrayElement, value) {
        for(var i=0; i<arrayElement.length; ++i) {
                arrayElement[i].style.display = 'block';
        }
}

function worldclock_setAllValue(arrayElement, value) {
	for(var i=0; i<arrayElement.length; ++i) {
		arrayElement[i].value = value;
	}
}

function worldclock_setChecked(arrayElement, checked) {
	for(var i=0; i<arrayElement.length; ++i) {
		arrayElement[i].checked = checked;
	}
}

function worldclock_setSelected(arrayElement, oldSelected, newSelected) {
        for(var i=0; i<arrayElement.length; ++i) {
		for(var j=0; j<arrayElement[i].options.length; ++j) {
			if(arrayElement[i].options[j].value == oldSelected) {	
				arrayElement[i].options[j].selected = false;
			}
			if(arrayElement[i].options[j].value == newSelected) {
				arrayElement[i].options[j].selected = true;
			}
		}
        }
}

function worldclock_setParameter(id, city, tz, dst) {
        worldclockIdEdit = id;
        worldclockCityEdit = city;
        worldclockTzEdit = tz;
        worldclockDstEdit = dst;
}

function worldclock_cancelEdit() {
	var worldclockTzEditOld = worldclockTzEdit;
	worldclock_setParameter(-1, '', -1, false);

        var arrayEdit = worldclock_getElementsByClass('worldclock_edit');
        var arrayIdEdit = worldclock_getElementsByClass('worldclock_id_edit');
        var arrayCityEdit = worldclock_getElementsByClass('worldclock_city_edit');
        var arrayTzEdit = worldclock_getElementsByClass('worldclock_tz_edit');
        var arrayDstEdit = worldclock_getElementsByClass('worldclock_dst_edit');

        worldclock_setAllValue(arrayIdEdit, worldclockIdEdit);
        worldclock_setAllValue(arrayCityEdit, worldclockCityEdit);
        worldclock_setChecked(arrayDstEdit, worldclockDstEdit);
	worldclock_setSelected(arrayTzEdit, worldclockTzEditOld, worldclockTzEdit);
        worldclock_setDisplayNone(arrayEdit);
}

function worldclock_editClock(id, city, tz, dst) {
	var worldclockTzEditOld = worldclockTzEdit;
	worldclock_setParameter(id, city, tz, dst);

	var arrayEdit = worldclock_getElementsByClass('worldclock_edit');
	var arrayIdEdit = worldclock_getElementsByClass('worldclock_id_edit');
	var arrayCityEdit = worldclock_getElementsByClass('worldclock_city_edit');
	var arrayTzEdit = worldclock_getElementsByClass('worldclock_tz_edit');
	var arrayDstEdit = worldclock_getElementsByClass('worldclock_dst_edit');

	worldclock_setAllValue(arrayIdEdit, worldclockIdEdit);
	worldclock_setAllValue(arrayCityEdit, worldclockCityEdit);
	worldclock_setChecked(arrayDstEdit, worldclockDstEdit);
	worldclock_setSelected(arrayTzEdit, worldclockTzEditOld, worldclockTzEdit);
	worldclock_setDisplayBlock(arrayEdit);
}
