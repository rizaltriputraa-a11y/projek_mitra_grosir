// Auto dismiss alerts
setTimeout(() => {
    document.querySelectorAll('.alert-dismissible').forEach(el => {
        const alert = bootstrap.Alert.getOrCreateInstance(el);
        alert.close();
    });
}, 5000);

// Quantity input validation
document.querySelectorAll('input[type="number"]').forEach(input => {
    input.addEventListener('change', () => {
        const min = parseInt(input.min) || 1;
        const max = parseInt(input.max) || Infinity;
        let val = parseInt(input.value) || min;
        if (val < min) val = min;
        if (val > max) val = max;
        input.value = val;
    });
});