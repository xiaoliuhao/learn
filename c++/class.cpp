#include <iostream>
using namespace std;
class Clock{
public:
	Clock(int newH, int newM, int newS);//构造函数
	void setTime(int newH = 0, int newM = 0, int newS = 0);
	void showTime();
private:
	int hour,minute,second;
};
//构造函数的实现
Clock::Clock(int newH, int newM, int newS):
hour(newH),minute(newM),second(newS){	 //如果是简单的赋值这样要方便简单一些
}
//等同于以下的代码
// Clock::Clock(int newH, int newM, int newS){
// 	hour = newH;
// 	minute = newM;
// 	second = nweS;
// }

void Clock::setTime(int newH, int newM, int newS){
	hour = newH;
	minute = newM;
	second = newS;
}
void Clock::showTime(){
	cout<<hour<<":"<<minute<<":"<<second<<endl;
}

int main(){
	Clock MyClock(10,0,0);
	MyClock.showTime();
	return 0;
}

