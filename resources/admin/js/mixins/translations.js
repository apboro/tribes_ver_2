export default {
    methods: {
        translateStatus(value) {
            if (value === null) return;
            let str = value.trim().toUpperCase();
            if (str === 'REFUNDED') {
                return 'Возвращён'
            } else if (str === 'COMPLETED') {
                return 'Завершён'
            } else if (str === 'CONFIRMED') {
                return 'Подтверждён'
            } else if (str === 'AUTHORIZED') {
                return 'Зарезервирован'
            } else if (str === 'NEW') {
                return 'Новый'
            }
        },

        translatePaymentType(value) {
            let str = value.trim().toLowerCase();
            if (str === 'donate') {
                return 'Донат'
            } else if (str === 'tariff') {
                return 'Тариф'
            } else if (str === 'course') {
                return 'Медиаконтент'
            } else if (str === 'payout') {
                return 'Выплата'
            }
        }
    }
}