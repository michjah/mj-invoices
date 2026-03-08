import { computed } from "vue"

export function useVat(items) {

    const toCents = (value) => Math.round(value * 100)
    const fromCents = (value) => value / 100

    const cartSummary = computed(() => {

        let totalNet = 0
        let totalVat = 0
        let totalGross = 0

        items.value.forEach(item => {

            const net = toCents(item.price) * item.quantity
            const vat = Math.round(net * item.vatRate / 100)
            const gross = net + vat

            totalNet += net
            totalVat += vat
            totalGross += gross
        })

        return {
            totalNet: fromCents(totalNet),
            totalVat: fromCents(totalVat),
            totalGross: fromCents(totalGross)
        }
    })

    return {
        cartSummary
    }
}
