def fibo(n):
    if n<=0:
        return None
    elif n==1 or n==2:
        return 1
    else:
        a = 1
        b = 1
        count = 2
        while count < n:
            c = a+b
            a = b
            b = c
            count +=1
        return b


nval = int(input("Masukkan nilai n >> "))
hasil = fibo(nval)
if hasil is not None:
    print(f"Fibonacci ke {nval} adalah {hasil}")
else:
    print("nilai n harus lebih dari 0")