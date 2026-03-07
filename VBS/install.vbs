' Made by ChiceAffy
' https://github.com/ChiceAffy/StudyPad-CrackDoc

' 获取管理员权限
Set WshShell = WScript.CreateObject("WScript.Shell") 
If WScript.Arguments.Length = 0 Then 
  Set ObjShell = CreateObject("Shell.Application") 
  ObjShell.ShellExecute "wscript.exe" _ 
  , """" & WScript.ScriptFullName & """ RunAsAdministrator", , "runas", 1 
  WScript.Quit 
End if 

' 下载 hosts 文件
Set Post = CreateObject("Msxml2.XMLHTTP")
Post.Open "GET", "https://studypad.ycyz.top/hosts", False
Post.Send()
Set aGet = CreateObject("ADODB.Stream")
aGet.Mode = 3
aGet.Type = 1
aGet.Open()
aGet.Write(Post.responseBody)
aGet.SaveToFile "./hosts", 2
aGet.Close()

' 替换 hosts 文件
Const OverwriteExisting=True
Set objFSO=CreateObject("Scripting.FileSystemObject")
objFSO.CopyFile "./hosts","C:\Windows\System32\drivers\etc\hosts", OverwriteExisting

' 删除 下载hosts 文件缓存
Dim fso, filePath
filePath = "./hosts"
Set fso = CreateObject("Scripting.FileSystemObject")
fso.DeleteFile(filePath)

' 刷新 DNS 缓存
WshShell.Run "ipconfig /flushdns", 0, True

' 更改热点最大连接数
Set objShell = CreateObject("WScript.Shell")
strKeyPath = "HKEY_LOCAL_MACHINE\SYSTEM\CurrentControlSet\Services\icssvc\Settings"
strValueName = "WifiMaxPeers"
strValue = 100
On Error Resume Next
objShell.RegRead strKeyPath & "\" & strValueName
If Err.Number <> 0 Then
    objShell.RegWrite strKeyPath & "\" & strValueName, strValue, "REG_DWORD"
Else
    objShell.RegDelete strKeyPath & "\" & strValueName
    objShell.RegWrite strKeyPath & "\" & strValueName, strValue, "REG_DWORD"
End If
On Error GoTo 0

' 重启电脑
WshShell.Run "shutdown /r /t 0", 0, True