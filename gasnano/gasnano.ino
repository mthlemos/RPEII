  
#include <SoftwareSerial.h>

SoftwareSerial esp8266(5, 4); // RX, TX 

// Programa : Alarme com sensor de gas MQ-2
String buffer;

void waitAndEcho(int t){
  buffer="";
  unsigned long start = millis();
  do{
    if(esp8266.available()){
      buffer += (char)esp8266.read();
    }
  }while(millis() < start+t);
    buffer.replace("\r","\\r");
    buffer.replace("\n","\\n");
    //Serial.println(buffer);
}

bool waitAndEcho(int t, String s){
  buffer="";
  unsigned long start = millis();
  unsigned long last = 0;
  unsigned int n = s.length();
  bool ret = false;
  do{
    if(esp8266.available()){
      buffer += (char)esp8266.read();
      last = millis();
      if(buffer.length() >= n){
        if(buffer.substring(buffer.length()-n).equals(s)) {
          ret = true;
          break;
        }
      }
    }
  }while(millis() < start+t);
    buffer.replace("\r","\\r");
    buffer.replace("\n","\\n");
    //Serial.println(buffer);
    return ret;
}
// Definicoes dos pinos ligados ao sensor 
int pin_d0 = 7;
int pin_a0 = A0;

int nivel_sensor = 245;

//Porta ligada ao pino IN1 do modulo
int porta_rele1 = 8;
//Porta ligada ao pino IN2 do modulo
int porta_rele2 = 9;



void setup()
{
  //Define pinos para o rele como saida
  pinMode(porta_rele1, OUTPUT); 
  pinMode(porta_rele2, OUTPUT);
  
  // Define os pinos de leitura do sensor como entrada
  pinMode(pin_d0, INPUT);
  pinMode(pin_a0, INPUT);

  digitalWrite(porta_rele1, HIGH); //Desliga rele 1

  // Inicializa a serial
  Serial.begin(9600);
  delay(2000);
  esp8266.begin(9600);
  esp8266.println("AT+RST");
  waitAndEcho(3000,"ready\r\n");
  esp8266.println("AT+CWMODE=3");
  waitAndEcho(300,"OK\r\n");
  esp8266.println("AT+CWJAP=\"Ap1b\",\"copel784405\"");
  waitAndEcho(15000,"OK\r\n");
  esp8266.println("AT+CIPMUX=0");
  waitAndEcho(100,"OK\r\n");
}

void sendData() {
  String postString = "POST /onesignaltest.php HTTP/1.1\r\n"
                      "Host: rpesite.000webhostapp.com\r\n"
                      "Cache-Control: no-cache\r\n"
                      "Content-Type: application/x-www-form-urlencoded; charset=utf-8\r\n"
                      "Content-Length: 46\r\n"
                      "\r\n"
                      "titulo=ALERTA!!!&mensagem=O GAS ESTA VAZANDO!\r\n";
  esp8266.println("AT+CIPSEND="+String(postString.length()));
  //waitAndEchoNoPrint(50);
  waitAndEcho(50,">");
  esp8266.print(postString);
  waitAndEcho(3000);
}

void getData() {
  String postString = "GET /gas/read.php HTTP/1.1\r\n"
                      "Host: rpesite.000webhostapp.com\r\n"
                      "Accept: application/x-www-form-urlencoded; charset=utf-8\r\n"
                      "\r\n";
  esp8266.println("AT+CIPSEND="+String(postString.length()));
  //waitAndEchoNoPrint(50);
  waitAndEcho(50,">");
  esp8266.print(postString);
  waitAndEcho(3000);
}

void loop()
{
  String response;
  int continuar = 0;
  unsigned long responseDelay, startTime, interval;
  // Le os dados do pino analogico A0 do sensor
  int valor_analogico = analogRead(pin_a0);

  //Serial.print(" Pino A0 : ");
  //Serial.println(valor_analogico);
  
  esp8266.println("AT+CIPSTART=\"TCP\",\"rpesite.000webhostapp.com\",80"); //(Re)abre a conexao TCP com o site
  waitAndEcho(5000,"CONNECT\r\n\r\nOK\r\n");
  getData();
  response = buffer.substring(buffer.indexOf('{')+1,buffer.indexOf('}'));
  responseDelay = response.toInt();
  interval = responseDelay;
  esp8266.println("AT+CIPCLOSE"); //Fecha conexão TCP com o site
  
  if(responseDelay > 0){
    continuar = 1;
  }
  
  if(continuar == 1){

    startTime = millis ();
    
    while(continuar == 1){
      //Serial.print("LIGADO");
      //Serial.println(millis () - startTime);
      if(millis () - startTime <= interval){
        valor_analogico = analogRead(pin_a0);
        if(valor_analogico > nivel_sensor){
          digitalWrite(porta_rele1, HIGH); //Desliga rele 1
                esp8266.println("AT+CIPSTART=\"TCP\",\"rpesite.000webhostapp.com\",80"); //(Re)abre a conexao TCP com o site
                waitAndEcho(5000,"CONNECT\r\n\r\nOK\r\n");
                sendData();
                esp8266.println("AT+CIPCLOSE"); //Fecha conexão TCP com o site
          continuar = 0;
          //Serial.println("DESLIGADO");
        }
        else{
          digitalWrite(porta_rele1, LOW);  //Liga rele 1
            //delay(500);
        }
        
      }
      else{
        continuar = 0;
        digitalWrite(porta_rele1, HIGH); //Desliga rele 1
        //Serial.println("DESLIGADO");
      }
      
    }
    
  }
  delay(2000);
}