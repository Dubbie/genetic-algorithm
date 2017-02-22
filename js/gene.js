var Gene = function(code) {
  if (code) {
    this.code = code;
  }

  this.cost = 9999;
};

Gene.prototype.code = '';
Gene.prototype.random = function(length) {
  while (length--) {
    this.code += String.fromCharCode(
      Math.floor(
        Math.random() * 255
      )
    );
  }
};
Gene.prototype.calcCost = function(compareTo) {
  var total = 0;

  for (i = 0; i < this.code.length; i++) {
    total += (this.code.charCodeAt(i) - compareTo.charCodeAt(i)) * (this.code.charCodeAt(i) - compareTo.charCodeAt(i));
  }

  this.cost = total;
};
Gene.prototype.mate = function(gene) {
  var pivot = Math.round(this.code.length / 2) - 1;

  var child1 = this.code.substr(0, pivot) + gene.code.substr(pivot);
  var child2 = gene.code.substr(0, pivot) + this.code.substr(pivot);

  return [new Gene(child1), new Gene(child2)];
};

Gene.prototype.mutate = function(chance) {
  if (Math.random() > chance)
    return;

  var bigIncrements = this.cost > 100;
  var index         = Math.floor(Math.random() * this.code.length);
  var upOrDown      = Math.random() > 0.5 ? 1 : -1;

  // if (bigIncrements) {
  //   upOrDown *= 2;
  // };

  var newChar  = String.fromCharCode(this.code.charCodeAt(index) + upOrDown);
  var newWord  = this.code.substr(0, index) + newChar + this.code.substr(index + 1, this.code.length);

  this.code = newWord;
};
