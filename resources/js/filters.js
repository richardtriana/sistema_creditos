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

export { dollarFilter }
