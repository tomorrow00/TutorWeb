# coding=utf8
import MySQLdb

conn = MySQLdb.connect(
	host='localhost',
	user='root',
	passwd='123456',
	db='TeacherSchema'
)
cur = conn.cursor()

cur.execute("SELECT COUNT(Teacher_Name) FROM Teacher;")
count = cur.fetchall()
count = count[0][0]
print count

for i in range(count):
	try:
		sql = "SELECT Teacher_Photo FROM Teacher WHERE Teacher_ID=" + str(i) + ";"
		cur.execute(sql)
		teacher = cur.fetchone()
		new_photo = teacher[0][:-4] + ".jpg"
		print new_photo
		sql_update = "UPDATE Teacher SET Teacher_Photo='" + str(new_photo) + "' WHERE Teacher_ID=" + str(i) + ";"
		cur.execute(sql_update)
		conn.commit()
	except:
		continue

