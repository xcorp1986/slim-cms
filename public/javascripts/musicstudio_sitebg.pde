float x1, y1, x2, y2, x3, y3, x4, y4;
int min_in, min_out, max_in, max_out;
void setup() {
  size(1200, 1200, P2D);
  background(255);
  noLoop();
}
void draw() {
  fill(234, 81, 96);
  noStroke();

//  min_out_top = (height/)
 max_out_top = height + (height/2);

min_out =  - (height/5);
min_in =  height/3;
max_in =  height-(height/3);
max_out =  height + (height/5);
vernieuw();
quad(x1, y1, x2, y2, x3, y3, x4, y4);
noFill();
stroke(0,0,255);

  int c = (width - height)/2;

x1 = c+min_out;
y1 = 100;
x2 = c+max_in;
y2 = 100;
x3 = c+max_in;
y3 = max_in;
x4 = c+min_out;
y4 = max_in;

//quad(x1, y1, x2, y2, x3, y3, x4, y4);

stroke(0,255,0);


x1 = c+min_in;
y1 = 270;
x2 = c+max_out;
y2 = 270;
x3 = c+max_out;
y3 = max_out;
x4 = c+min_in;
y4 = max_out;


//quad(x1, y1, x2, y2, x3, y3, x4, y4);

}


void vernieuw() {
  int c = (width - height)/2;
  x1 = random(c + min_out, c + min_in);
  y1 = random(100, 270);
  x2 = random(c + max_in, c + max_out);
  y2 = random(100, 270);
  x3 = random(c + max_in, c + max_out);
  y3 = random(max_in, max_out);
  x4 = random(c + min_out, c + min_in);
  y4 = random(max_in, max_out);
}
