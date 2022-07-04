String.prototype.ucFirst = function() {
    let str = this;
    if(str.length) {
        str = str.charAt(0).toUpperCase() + str.slice(1);
    }
    return str;
};

Array.prototype.getUnique = function() {
    return this.filter((value, index, self) => {
        return self.indexOf(value) === index;
    });
};

