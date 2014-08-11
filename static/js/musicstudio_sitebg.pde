float x1, y1, x2, y2, x3, y3, x4, y4;
int min_in, min_out, max_in, max_out;

void setup() {
  size(1200, 1200);

  min_out =  - (height/5);
  min_in =  height/3;
  max_in =  height-(height/3);
  max_out =  height + (height/5);


  background(255);
  fill(234, 81, 96);
  noStroke();
  vernieuw();  
  quad(x1, y1, x2, y2, x3, y3, x4, y4);
}

public void vernieuw() {
  // center (landscape)
  int c = (width - height)/2;

  x1 = random(c + min_out, c + min_in);
  y1 = random(min_out, min_in); 

  x2 = random(c + max_in, c + max_out);
  y2 = random(min_out, min_in); 

  x3 = random(c + max_in, c + max_out);
  y3 = random(max_in, max_out); 

  x4 = random(c + min_out, c + min_in);
  y4 = random(max_in, max_out);
}


