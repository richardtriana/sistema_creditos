import numeral from 'numeral';
import moment from 'moment';
import 'moment/locale/es'  // without this line it didn't work
moment.locale('es')

const dollarFilter = function (value) {
	if (!value) {
		return '$ 0'
	}

	return numeral(value).format('($0,0)')
}

const affectation = function (value, type) {
		let data = value.split('#');
		var result = value;

		try{
			switch(type){
				case 'expense':
						if(data[1] !== undefined && data[3] !== undefined)
								result = `${data[1]} - ${data[3]}`;
					break;
				case 'entry':
					if(data[0] !== undefined)
							result = `${data[0]}`;
					break;
				default:
					result = value;
			}
		}catch(ex){
			return value;
		}

		return result;
}

export { dollarFilter, affectation}
