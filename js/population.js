var Population = function(goal, size) {
  this.members          = [];
  this.goal             = goal;
  this.generationNumber = 0;
  this.timer            = null;
  this.improvement      = 0;

  while (size--) {
    var gene = new Gene();
    gene.random(this.goal.length);
    this.members.push(gene);
  }
};

Population.prototype.sort = function() {
  this.members.sort(function(a, b) {
    return a.cost - b.cost;
  });
};

Population.prototype.display = function() {
  document.getElementById('generations').textContent = this.generationNumber;
  document.getElementById('best-estimate').textContent = this.members[0].code;
};

Population.prototype.generation = function() {
  for (var i = 0; i < this.members.length; i++) {
    this.members[i].calcCost(this.goal);
  }

  this.sort();
  this.display();
  var children = this.members[0].mate(this.members[1]);
  this.members.splice(this.members.length - 2, 2, children[0], children[1]);

  for (var i = 0; i < this.members.length; i++) {
    this.members[i].mutate(0.5);
    this.members[i].calcCost(this.goal);
    if (this.members[i].code == this.goal) {
      this.sort();
      this.display();
      return true;
    }
  }
  this.generationNumber++;
  var scope = this;
  this.timer = setTimeout(function() { scope.generation(); } , 20);
};
