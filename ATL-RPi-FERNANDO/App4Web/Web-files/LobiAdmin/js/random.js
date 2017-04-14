/**
 * Generate n random numbers each of them in between min and max values
 * 
 * @param {Integer} n
 * @param {Integer} min
 * @param {Integer} max
 * @returns {Array} 
 */
function randomIntegers(n, min, max) {
    var arr = [];
    for (var i = 0; i < n; i++) {
        arr.push(Math.round(min + Math.random() * (max - min)));
    }
    return arr;
}