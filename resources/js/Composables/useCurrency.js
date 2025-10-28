import { computed, ref } from 'vue';

export function useCurrency(exchangeRateProp = 13.5000) {
    const exchangeRate = ref(exchangeRateProp);
    
    const convertPrice = (amount, from, to) => {
        if (from === to) {
            return amount;
        }
        
        // Convert to USD first
        const usdAmount = from === 'USD' ? amount : amount / exchangeRate.value;
        
        // Convert to target currency
        return to === 'USD' ? usdAmount : usdAmount * exchangeRate.value;
    };
    
    const formatPrice = (amount, currency) => {
        const symbol = currency === 'USD' ? '$' : 'ZWG$';
        return `${symbol}${parseFloat(amount).toFixed(2)}`;
    };
    
    const getDualPrice = (price, currency) => {
        const otherCurrency = currency === 'USD' ? 'ZWG' : 'USD';
        const convertedPrice = convertPrice(price, currency, otherCurrency);
        
        return {
            primary: formatPrice(price, currency),
            primaryAmount: parseFloat(price),
            primaryCurrency: currency,
            secondary: `≈ ${formatPrice(convertedPrice, otherCurrency)}`,
            secondaryAmount: convertedPrice,
            secondaryCurrency: otherCurrency
        };
    };
    
    const getCurrencySymbol = (currency) => {
        return currency === 'USD' ? '$' : 'ZWG$';
    };
    
    return {
        exchangeRate,
        convertPrice,
        formatPrice,
        getDualPrice,
        getCurrencySymbol
    };
}





